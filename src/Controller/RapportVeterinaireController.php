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
    public function new(Request $request, AnimalRepository $animalRepository, AlimentationRepository $alimentationRepository, int $id): Response
    {
        // Vérifier si l'ID est valide
        if (!$id) {
            throw new \Exception("L'ID de l'animal est manquant");
        }
    
        // Trouver l'animal par son ID
        $animal = $animalRepository->find($id);
        if (!$animal) {
            throw $this->createNotFoundException('Animal non trouvé');
        }
    
        // Récupérer les consommations alimentaires pour cet animal
        $alimentations = $alimentationRepository->findBy(['animal' => $animal]);
    
        // Debug : Vérifier si des alimentations ont été récupérées
        dump($alimentations); // Affichez les alimentations récupérées
    
        // Créer un rapport vétérinaire
        $rapport = new RapportVeterinaire();
        $rapport->setAnimal($animal);
    
        // Créer le formulaire
        $form = $this->createForm(RapportVeterinaireType::class, $rapport);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Enregistrer le rapport vétérinaire
                $this->entityManager->persist($rapport);
                $this->entityManager->flush();
    
                // Vérifier que des alimentations existent
                if (!empty($alimentations)) {
                    foreach ($alimentations as $alimentation) {
                        // Créer un nouvel objet AnimalFeeding pour chaque alimentation
                        $animalFeeding = new AnimalFeeding();
                        $animalFeeding->setFood($alimentation->getNourriture());
                        $animalFeeding->setFeedingTime(new \DateTime()); // Horodatage actuel
                        $animalFeeding->setQuantity($alimentation->getGrammage());
                        $animalFeeding->setRapportVeterinaire($rapport);
    
                        // Associer l'animal avant de persister
                        $animalFeeding->setAnimal($animal); // Associer l'animal
    
                        // Vérification que l'animal est bien défini avant la persistance
                        if ($animalFeeding->getAnimal() === null) {
                            throw new \Exception('L\'animal doit être défini avant la persistance');
                        }
    
                        // Debug : Vérification que AnimalFeeding est correctement créé
                        dump($animalFeeding); // Affiche l'objet AnimalFeeding
    
                        // Persister l'alimentation
                        $this->entityManager->persist($animalFeeding);
                    }
    
                    // Effectuer la sauvegarde après avoir persisté toutes les alimentations
                    $this->entityManager->flush();
                }
    
                // Rediriger vers la page du rapport après la soumission
                return $this->redirectToRoute('rapport_veterinaire_show', ['id' => $rapport->getId()]);
    
            } catch (\Exception $e) {
                // Gérer les exceptions et afficher les erreurs
                dump($e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement du rapport vétérinaire.');
            }
        }
    
        // Rendu du formulaire et des alimentations
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

}
