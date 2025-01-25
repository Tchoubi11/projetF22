<?php

namespace App\Controller;

use App\Entity\Alimentation;
use App\Form\AlimentationType;
use App\Repository\AlimentationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/employe/alimentation')]
class AlimentationController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'employe_alimentation_index', methods: ['GET'])]
    public function index(AlimentationRepository $alimentationRepository): Response
    {
        return $this->render('employe/alimentation/index.html.twig', [
            'alimentations' => $alimentationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'employe_alimentation_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $alimentation = new Alimentation();
        $form = $this->createForm(AlimentationType::class, $alimentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($alimentation);
            $entityManager->flush();

            $this->addFlash('success', 'Consommation ajoutée avec succès.');
            return $this->redirectToRoute('employe_alimentation_index');
        }

        return $this->render('employe/alimentation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
