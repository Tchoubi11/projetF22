<?php

namespace App\Controller;

use App\Entity\RapportVeterinaire;
use App\Entity\Animal;
use App\Entity\AnimalFeeding;
use App\Form\RapportVeterinaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\AnimalRepository;
use App\Repository\AlimentationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/rapport_veterinaire')]
class RapportVeterinaireController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Liste des rapports vétérinaires
    #[Route('/', name: 'rapport_veterinaire_index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'prenom',
                'placeholder' => 'Sélectionner un animal',
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);
        $animal = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $animal = $data['animal'];
        }

        $rapports = $animal ? $em->getRepository(RapportVeterinaire::class)->findBy(['animal' => $animal])
                            : $em->getRepository(RapportVeterinaire::class)->findAll();

        return $this->render('rapport_veterinaire/index.html.twig', [
            'form' => $form->createView(),
            'animal' => $animal,
            'rapports' => $rapports,
        ]);
    }

    
    #[Route('/new/{id}', name: 'rapport_veterinaire_new')]
    public function new(
        Request $request,
        AnimalRepository $animalRepository,
        AlimentationRepository $alimentationRepository,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        $rapport = new RapportVeterinaire();
        $form = $this->createForm(RapportVeterinaireType::class, $rapport);
    
        $form->handleRequest($request);
    
        
        $alimentations = [];
        $animal = $animalRepository->find($id);
if (!$animal) {
    throw $this->createNotFoundException("Animal non trouvé.");
}
$rapport->setAnimal($animal);

    
        if ($form->isSubmitted() && $form->isValid()) {
            $animal = $form->get('animal')->getData();
            dump($animal);
           
            if (!$animal) {
                $this->addFlash('error', 'Un animal doit être sélectionné.');
                return $this->redirectToRoute('rapport_veterinaire_new');
            }
    
           
            if (!$animal->getId()) {
                throw new \Exception("L'animal associé n'a pas d'ID valide.");
            }
    
            
            $rapport->setAnimal($animal);
            
            
            $entityManager->persist($rapport);
            $entityManager->flush();
    
            
            $alimentations = $alimentationRepository->findBy(['animal' => $animal]);
    
            
            foreach ($alimentations as $alimentation) {
                $feeding = new AnimalFeeding();
                $feeding->setAnimal($animal); 
                $feeding->setRapportVeterinaire($rapport); 
                $feeding->setFood($alimentation->getFood());
                $feeding->setFeedingTime($alimentation->getFeedingTime());
                $feeding->setQuantity($alimentation->getQuantity());
    
                $entityManager->persist($feeding); 
            }
    
            $entityManager->flush(); // Sauvegarde des alimentations et du rapport
    
            return $this->redirectToRoute('rapport_veterinaire_success'); 
        }
    
        return $this->render('rapport_veterinaire/new.html.twig', [
            'form' => $form->createView(),
            'alimentations' => $alimentations, 
        ]);
    }

    
    
    
    
    // Affichage d'un rapport vétérinaire
    #[Route('/{id}', name: 'rapport_veterinaire_show')]
    public function show(int $id): Response
    {
        $rapport = $this->entityManager->getRepository(RapportVeterinaire::class)->find($id);

        if (!$rapport) {
            throw $this->createNotFoundException('Rapport vétérinaire non trouvé.');
        }

        return $this->render('rapport_veterinaire/show.html.twig', [
            'rapport' => $rapport,
        ]);
    }

    // Suppression d'un rapport vétérinaire
    #[Route('/delete/{id}', name: 'rapport_veterinaire_delete', methods: ['POST'])]
    public function delete(Request $request, RapportVeterinaire $rapport, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rapport->getId(), $request->request->get('_token'))) {
            $em->remove($rapport);
            $em->flush();
            $this->addFlash('success', 'Rapport vétérinaire supprimé avec succès.');
        }

        return $this->redirectToRoute('rapport_veterinaire_index');
    }

    #[Route('/dashboard', name: 'rapport_veterinaire_dashboard')]
public function dashboard(): Response
{
    return $this->render('rapport_veterinaire/veterinaire_dashboard.html.twig', []);
}

#[Route('/get-alimentation/{id}', name: 'get_alimentation', methods: ['GET'],options:['expose'=>true])]
public function getAlimentation(
    AnimalRepository $animalRepository,
    AlimentationRepository $alimentationRepository,
    int $id
): JsonResponse {
    $animal = $animalRepository->find($id);

    if (!$animal) {
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    $alimentations = $alimentationRepository->findBy(['animal' => $animal]);

    if (!$alimentations) {
        return new JsonResponse([], Response::HTTP_OK); // Retourne un tableau vide si pas d'alimentation
    }

    // Transformer les objets en tableau JSON
    $data = [];
    //foreach ($alimentations as $alimentation) {
     //   $data[] = [
     //       'dateHeure' => $alimentation->getDateHeure()->format('H:i'),
      //      'nourriture' => $alimentation->getNourriture(),
      //      'grammage' => $alimentation->getGrammage(),
     //   ];
    //}
    $data['html'] = $this->renderView('rapport_veterinaire/_alimentations.html.twig',['alimentations' =>$alimentations]) ;
    dump($data['html']);
    return new JsonResponse($data);
}


}
