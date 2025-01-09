<?php
namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route('/upload-image', name: 'image_upload')]
    public function upload(Request $request, EntityManagerInterface $entityManager): Response
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
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

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
}
