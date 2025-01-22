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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
    #[Route('/new', name: 'rapport_veterinaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $rapport = new RapportVeterinaire();

        $form = $this->createFormBuilder($rapport)
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date du rapport',
            ])
            ->add('detail', TextareaType::class, [
                'label' => 'Détail',
            ])
            ->add('animal', null, [
                'choice_label' => 'prenom',
                'label' => 'Animal',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer le rapport',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rapport);
            $em->flush();

            $this->addFlash('success', 'Rapport vétérinaire créé avec succès.');

            return $this->redirectToRoute('rapport_veterinaire_list');
        }

        return $this->render('admin/rapport_veterinaire/new.html.twig', [
            'form' => $form->createView(),
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
