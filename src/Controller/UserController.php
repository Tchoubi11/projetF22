<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create', name: 'admin_user_create')]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Création du cache
    $cache = new FilesystemAdapter('', 3600); // Initalisation de $cache

    $cache->delete('users_list'); // Invalide le cache

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', EmailType::class, ['required' => false])
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Employé' => 'ROLE_EMPLOYE',
                    'Vétérinaire' => 'ROLE_VETERINAIRE',
                ],
                'expanded' => false,
                'multiple' => true,
            ])
            ->add('nom', TextType::class, ['required' => true])
            ->add('prenom', TextType::class, ['required' => true])
            ->add('email', EmailType::class, ['required' => true])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getEmail()) {
                $this->addFlash('error', 'L\'email est obligatoire.');
                return $this->redirectToRoute('admin_user_create');
            }

            if (!$user->getUsername()) {
                $user->setUsername($user->getEmail());
            }

            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_user_notify', ['id' => $user->getId()]);
        }

        return $this->render('admin/user_create.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $em): Response
    {
         // Création du cache
        $cache = new FilesystemAdapter('', 3600); // Initalisation de $cache

        $cache->delete('users_list'); // Invalide le cache

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            throw $this->createAccessDeniedException('Impossible de modifier un compte Administrateur.');
        }

        $form = $this->createFormBuilder($user)
            ->add('username', EmailType::class, ['required' => false])
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Employé' => 'ROLE_EMPLOYE',
                    'Vétérinaire' => 'ROLE_VETERINAIRE',
                ],
                'expanded' => false,
                'multiple' => true,
            ])
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getUsername()) {
                $user->setUsername($user->getEmail());
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Les rôles de l\'utilisateur ont été mis à jour avec succès.');
            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user_edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/list', name: 'admin_user_list')]
    public function listUsers(UserRepository $userRepository): Response
    {
        // Création du cache
    $cache = new FilesystemAdapter('',3600);

    // Récupère les utilisateurs depuis le cache si disponibles
    $users = $cache->get('users_list', function() use ($userRepository) {
        // Si non, récupère les utilisateurs depuis la base de données
        return $userRepository->findAll();
    });

        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/notify/{id}', name: 'admin_user_notify')]
    public function notifyUser(int $id, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $email = (new Email())
            ->from('noreply@arcadia.com')
            ->to($user->getEmail())
            ->subject('Votre compte a été créé')
            ->html($this->renderView('emails/notify_user.html.twig', ['user' => $user]));

        try {
            $mailer->send($email);
            $this->addFlash('success', 'Notification envoyée avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_user_list');
    }
}
