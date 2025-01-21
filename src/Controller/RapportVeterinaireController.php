<?php

namespace App\Controller;

use App\Entity\RapportVeterinaire;
use App\Form\RapportVeterinaireType;
use App\Repository\RapportVeterinaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rapport-veterinaire')]
class RapportVeterinaireController extends AbstractController
{
    #[Route('/new', name: 'rapport_veterinaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $rapportVeterinaire = new RapportVeterinaire();
        $form = $this->createForm(RapportVeterinaireType::class, $rapportVeterinaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rapportVeterinaire);
            $em->flush();

            $this->addFlash('success', 'Le rapport vétérinaire a été créé avec succès.');

            return $this->redirectToRoute('rapport_veterinaire_list');
        }

        return $this->render('rapport_veterinaire/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'rapport_veterinaire_list', methods: ['GET'])]
    public function index(RapportVeterinaireRepository $repository): Response
    {
        $rapports = $repository->findAll();

        return $this->render('rapport_veterinaire/index.html.twig', [
            'rapports' => $rapports,
        ]);
    }

    #[Route('/{id}', name: 'rapport_veterinaire_show', methods: ['GET'])]
    public function show(RapportVeterinaire $rapportVeterinaire): Response
    {
        return $this->render('rapport_veterinaire/show.html.twig', [
            'rapport' => $rapportVeterinaire,
        ]);
    }

    #[Route('/{id}/delete', name: 'rapport_veterinaire_delete', methods: ['POST'])]
    public function delete(Request $request, RapportVeterinaire $rapportVeterinaire, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rapportVeterinaire->getId(), $request->request->get('_token'))) {
            $em->remove($rapportVeterinaire);
            $em->flush();

            $this->addFlash('success', 'Le rapport vétérinaire a été supprimé avec succès.');
        }

        return $this->redirectToRoute('rapport_veterinaire_list');
    }
}
