<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/edit/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $em): Response
    {
        // Vérifie si on tente de modifier un compte administrateur
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            throw $this->createAccessDeniedException('Impossible de modifier un compte Administrateur.');
        }

        // Crée le formulaire pour modifier les rôles de l'utilisateur
        $form = $this->createFormBuilder($user)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Employé' => 'ROLE_EMPLOYE',
                    'Vétérinaire' => 'ROLE_VETERINAIRE',
                ],
                'expanded' => false,
                'multiple' => true,
                'label' => 'Rôles',
            ])
            ->getForm();

        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie à nouveau si le rôle admin est assigné dans les données soumises
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                throw $this->createAccessDeniedException('Impossible d\'attribuer le rôle Administrateur.');
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Les rôles de l\'utilisateur ont été mis à jour avec succès.');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/', name: 'user_list', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupère la liste des utilisateurs
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
