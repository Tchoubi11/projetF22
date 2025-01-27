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


#[Route('/admin/rapport_veterinaire')]
class RapportVeterinaireController extends AbstractController
{
    // Liste des rapports vétérinaires
    #[Route('/', name: 'rapport_veterinaire_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): Response
    {
        $rapports = $em->getRepository(RapportVeterinaire::class)->findAll();

        return $this->render('admin/rapport_veterinaire/list.html.twig', [
            'rapports' => $rapports,
        ]);
    }

    // Créer un nouveau rapport vétérinaire
    // Créer un nouveau rapport vétérinaire pour un animal spécifique
    #[Route('/new/{id}', name: 'rapport_veterinaire_new', methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'animal avec l'ID spécifié
        $animal = $em->getRepository(Animal::class)->find($id);
    
        if (!$animal) {
            throw $this->createNotFoundException('L\'animal n\'a pas été trouvé.');
        }
    
        // Créer un nouveau rapport vétérinaire et lier l'animal au rapport
        $rapport = new RapportVeterinaire();
        $rapport->setAnimal($animal);  // Lier l'animal au rapport vétérinaire
    
        // Créer le formulaire pour le rapport vétérinaire
        $form = $this->createFormBuilder($rapport)
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date du rapport',
            ])
            ->add('observations', TextareaType::class, [
                'label' => 'Observations',
                'attr' => [
            'placeholder' => 'Entrez vos observations ici', // Placeholder pour la zone de texte
             ],
            ])
            ->add('feedings', CollectionType::class, [
                'entry_type' => AnimalFeedingType::class, // Utilisation du formulaire pour AnimalFeeding
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'prenom',  // Affiche le nom de l'animal
                'data' => $animal,         // Pré-sélectionner l'animal
                      // Désactiver le champ pour qu'il ne soit pas modifiable
            ])
            ->add('habitatComment', TextareaType::class, [
                'label' => 'Commentaires sur l\'habitat',
           'attr' => [
            'placeholder' => 'Commentaires sur l\'habitat (facultatif)', // Placeholder pour habitat comment
            ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer le rapport',
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rapport);
            $em->flush();
    
            // Message flash pour le succès
            $this->addFlash('success', 'Rapport vétérinaire créé avec succès.');
    
            // Rediriger vers la liste des rapports
            return $this->redirectToRoute('rapport_veterinaire_list');
        }
    
        // Rendre le formulaire et passer l'animal au template
        return $this->render('admin/rapport_veterinaire/new.html.twig', [
            'form' => $form->createView(),
            'animal' => $animal,  // Passer l'animal au template pour le lien
        ]);
    }
    


    // Afficher les détails d'un rapport vétérinaire
    #[Route('/{id}', name: 'rapport_veterinaire_show', methods: ['GET'])]
    public function show(RapportVeterinaire $rapport): Response
    {
        return $this->render('admin/rapport_veterinaire/show.html.twig', [
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
