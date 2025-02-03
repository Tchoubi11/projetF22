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
        // Formulaire pour sélectionner un animal
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
            $animal = $data['animal']; // ici je récupère l'animal sélectionné
        }

        // Récupéreration des rapports vétérinaires, on filtre si un animal est sélectionné
        if ($animal) {
            $rapports = $em->getRepository(RapportVeterinaire::class)->findBy(['animal' => $animal]);
        } else {
            $rapports = $em->getRepository(RapportVeterinaire::class)->findAll();  // Si aucun animal sélectionné
        }

        return $this->render('rapport_veterinaire/index.html.twig', [
            'form' => $form->createView(),
            'animal' => $animal,
            'rapports' => $rapports,
        ]);
    }

    
    // Création d'un rapport vétérinaire
    #[Route('/new/{id}', name: 'rapport_veterinaire_new')]
    public function new(Request $request, EntityManagerInterface $em, AnimalRepository $animalRepository, int $id): Response
    {
        // Récupéreration de l'animal par son ID
        $animal = $animalRepository->find($id);
        
        if (!$animal) {
            throw $this->createNotFoundException('Animal non trouvé');
        }
    
        // Création d'un rapport vétérinaire et association avec l'animal
        $rapport = new RapportVeterinaire();
        $rapport->setAnimal($animal);  
    
        // Création du formulaire
        $form = $this->createForm(RapportVeterinaireType::class, $rapport);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // on associe chaque alimentation au rapport et à l'animal
            foreach ($rapport->getFeedings() as $feeding) {
                $feeding->setAnimal($animal);
                $feeding->setRapportVeterinaire($rapport);
                $em->persist($feeding);
            }
    
            // Enregistrement du rapport vétérinaire
            $em->persist($rapport);
            $em->flush();
    
            return $this->redirectToRoute('rapport_veterinaire_show', ['id' => $rapport->getId()]);
        }
    
        return $this->render('rapport_veterinaire/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    // Afficheage d'un rapport vétérinaire
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

    // Suppréssion un rapport vétérinaire
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
    #[Route('/veterinaire/dashboard', name: 'veterinaire_dashboard')]
    public function dashboard(): Response
    {
        // Logique pour le tableau de bord du vétérinaire
        return $this->render('rapport_veterinaire/veterinaire_dashboard.html.twig');
    }
}
