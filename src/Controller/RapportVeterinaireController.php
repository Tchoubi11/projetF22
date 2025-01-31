<?php

namespace App\Controller;

use App\Entity\RapportVeterinaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\AnimalFeedingType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Animal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


#[Route('/rapport_veterinaire')]
class RapportVeterinaireController extends AbstractController
{
    // Liste des rapports vétérinaires
    #[Route('/', name: 'rapport_veterinaire_list', methods: ['GET'])]
public function list(EntityManagerInterface $em): Response
{
    $rapports = $em->getRepository(RapportVeterinaire::class)->findAll();

    
    $animal = $em->getRepository(Animal::class)->findOneBy([]);

    return $this->render('/rapport_veterinaire/list.html.twig', [
        'rapports' => $rapports,
        'animal' => $animal, 
    ]);
}


     // Route pour le tableau de bord Vétérinaire
     #[Route('/veterinaire/dashboard', name: 'veterinaire_dashboard')]
     public function index(): Response
     {
     return $this->render('rapport_veterinaire/veterinaire_dashboard.html.twig');  
     }


#[Route('/new/{id}', name: 'rapport_veterinaire_new', methods: ['GET', 'POST'])]
public function new(int $id, Request $request, EntityManagerInterface $em): Response
{
    $animal = $em->getRepository(Animal::class)->find($id);

    if (!$animal) {
        throw $this->createNotFoundException('Animal non trouvé.');
    }

    $rapport = new RapportVeterinaire();
    $rapport->setAnimal($animal);

    $form = $this->createFormBuilder($rapport)
        ->add('date', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date du rapport',
        ])
        
        ->add('animal', EntityType::class, [
            'class' => Animal::class, 
            'choice_label' => 'prenom', 
            'data' => $animal,
        ])
        ->add('habitatComment', TextareaType::class, [
            'label' => 'Commentaires sur l\'habitat',
            'required' => false,
        ])
        ->add('detail', TextareaType::class, [
            'label' => 'Détails',
            'required' => true,
        ])
        ->add('feedings', CollectionType::class, [
            'entry_type' => AnimalFeedingType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ])
        ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier les feedings pour définir feedingTime si c'est null
        $feedings = $rapport->getFeedings();
        foreach ($feedings as $feeding) {
            // Si feedingTime est null, attribuer une valeur par défaut
            if ($feeding->getFeedingTime() === null) {
                $feeding->setFeedingTime(new \DateTime());  // Valeur par défaut: date et heure actuelles
            }
        }

        // Persist the report and feeding data
        $em->persist($rapport);
        $em->flush();

        $this->addFlash('success', 'Rapport vétérinaire créé avec succès.');
        return $this->redirectToRoute('rapport_veterinaire_show', ['id' => $rapport->getId()]);
    }

    return $this->render('/rapport_veterinaire/new.html.twig', [
        'form' => $form->createView(),
        'animal' => $animal,
    ]);
}




    


    // Afficher les détails d'un rapport vétérinaire
    #[Route('/{id}', name: 'rapport_veterinaire_show', methods: ['GET'])]
public function show(RapportVeterinaire $rapport): Response
{
    return $this->render('/rapport_veterinaire/show.html.twig', [
        'rapport' => $rapport,
    ]);
}


    // Supprimer un rapport vétérinaire
    #[Route('/delete/{id}', name: 'rapport_veterinaire_delete', methods: ['POST'])]
    public function delete(Request $request, RapportVeterinaire $rapport, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rapport->getId(), $request->request->get('_token'))) {
            $em->remove($rapport);
            $em->flush();

            $this->addFlash('success', 'Rapport vétérinaire supprimé avec succès.');
        }

        return $this->redirectToRoute('rapport_veterinaire_list');
    }
}
