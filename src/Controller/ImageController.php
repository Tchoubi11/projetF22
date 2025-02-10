<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ImageService;
use App\Form\ImageType;

class ImageController extends AbstractController
{
    #[Route('/upload-image', name: 'image_upload')]
    public function upload(Request $request, EntityManagerInterface $entityManager, #[Autowire('%images_directory%')] string $imageDirectory): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                // On génère un nom unique pour l'image
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                // On déplace le fichier dans le répertoire configuré
                try {
                    $imageFile->move(
                        $imageDirectory,
                        $newFilename
                    );
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement: ' . $e->getMessage());
                    return $this->redirectToRoute('image_upload');
                }

                // On enregistre le chemin dans l'entité
                $image->setImagePath($newFilename);
            }

            $entityManager->persist($image);
            $entityManager->flush();

            $this->addFlash('success', 'Image téléchargée avec succès !');

            return $this->redirectToRoute('image_list');
        }

        return $this->render('image/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/images', name: 'image_list')]
    public function list(ImageRepository $imageRepository): Response
    {
        $images = $imageRepository->findAll();

        return $this->render('image/list.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/upload-compressed', name: 'image_upload_compressed', methods: ['POST'])]
    public function uploadWithCompression(Request $request, ImageService $imageService): Response
    {
        $file = $request->files->get('image');

        if (!$file instanceof UploadedFile) {
            return $this->json(['error' => 'No image uploaded'], Response::HTTP_BAD_REQUEST);
        }

        // Déplacer l'image vers le dossier d'upload
        $filename = uniqid() . '.' . $file->guessExtension();
        $file->move($imageService->getUploadDir(), $filename);

        // Compression sans redimensionner
        $compressedFilename = $imageService->compressOnly($filename);

        if (!$compressedFilename) {
            return $this->json(['error' => 'Image processing failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['message' => 'Image uploaded and compressed successfully', 'path' => '/uploads/images/' . $compressedFilename]);
    }

    #[Route('/compress-all-images', name: 'compress_all_images')]
    public function compressAllImages(ImageRepository $imageRepository, ImageService $imageService, EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les images depuis la base de données
        $images = $imageRepository->findAll();

        // Traiter chaque image pour la compresser
        foreach ($images as $image) {
            $imagePath = $image->getImagePath();

            // Vérifier si le fichier existe dans le répertoire d'upload
            $fullPath = $imageService->getUploadDir() . $imagePath;
            if (file_exists($fullPath)) {
                // Compresser l'image sans redimensionner
                $compressedFilename = $imageService->compressOnly($imagePath);

                // Si vous souhaitez mettre à jour le chemin du fichier compressé dans la base de données
                if ($compressedFilename) {
                    // Exemple de mise à jour du chemin de l'image
                    $image->setImagePath($compressedFilename);
                    $entityManager->persist($image);
                    $entityManager->flush();
                }
            }
        }

        // Ajouter un message flash pour indiquer que le processus est terminé
        $this->addFlash('success', 'Toutes les images ont été compressées avec succès.');

        return $this->redirectToRoute('image_list'); // Retourner à la liste des images
    }
}
