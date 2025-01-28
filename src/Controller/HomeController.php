<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Avis;
use App\Entity\Habitat;
use App\Entity\User;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\HabitatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class HomeController extends AbstractController
{
    private $habitatRepository;
    private $avisRepository;
    private $entityManager;

    // Injection de HabitatRepository, AvisRepository et EntityManager via le constructeur
    public function __construct(
        HabitatRepository $habitatRepository,
        AvisRepository $avisRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->habitatRepository = $habitatRepository;
        $this->avisRepository = $avisRepository;
        $this->entityManager = $entityManager;
    }
    
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        // Récupération des avis visibles
        $avisVisibles = $this->avisRepository->findBy(['isVisible' => true]);

        // Créationd' un nouvel avis et traite le formulaire
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setVisible(false); // avis est invisible par défaut
            $this->avisRepository->save($avis, true);

            $this->addFlash('success', 'Votre avis a été soumis pour validation.');
            return $this->redirectToRoute('app_home');
        }

        // Contenu statique pour les critiques, habitats et services
        $reviews = [
            "Très belle expérience, mes enfants ont adoré voir les tigres!" => "Cathérine",
            "Un endroit magnifique et bien entretenu. Bravo à l'équipe!" => "Marcus",
            "Les habitats sont vraiment bien conçus. Un moment inoubliable." => "John",
        ];

        $habitats = [
            'Savane' => ['Lions', 'Girafes', 'Zèbres'],
            'Jungle' => ['Singes', 'Tigres', 'Léopards'],
            'Marais' => ['Couguar', 'Renard gris']
        ];

        $services = [
            'Restauration',
            'Visite du zoo en petit train',
            'Visites des habitats avec un guide (gratuit)'
        ];

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'avisVisibles' => $avisVisibles,
            'reviews' => $reviews,
            'habitats' => $habitats,
            'services' => $services,
        ]);
    }

    #[Route('/employe/avis', name: 'employe_avis')]
    public function gestionAvis(): Response
    {
        // Pour m'assurer que seuls les administrateurs et les utilisateurs peuvent accéder à la route
        $this->denyAccessUnlessGranted('ROLE_EMPLOYE');

        // Je récupère les avis non visibles
        $avisNonVisibles = $this->avisRepository->findBy(['isVisible' => false]);

        return $this->render('employe/avis.html.twig', [
            'avisNonVisibles' => $avisNonVisibles,
        ]);
    }

    #[Route('/employe/avis/valider/{id}', name: 'employe_avis_valider')]
    public function validerAvis(Avis $avis): Response
    {
        $avis->setVisible(true);
        $this->avisRepository->save($avis, true);

        $this->addFlash('success', 'Avis validé avec succès.');
        return $this->redirectToRoute('employe_avis');
    }

    #[Route('/employe/avis/supprimer/{id}', name: 'employe_avis_supprimer')]
    public function supprimerAvis(Avis $avis): Response
    {
        $this->avisRepository->remove($avis, true);

        $this->addFlash('success', 'Avis supprimé avec succès.');
        return $this->redirectToRoute('employe_avis');
    }

    #[Route('/services', name: 'app_services')]
    public function services(): Response
    {
        $services = [
            'Restauration' => 'Dégustez une variété de plats savoureux dans nos restaurants situés au cœur du zoo.',
            'Visite du zoo en petit train' => 'Explorez tout le zoo sans effort grâce à notre petit train.',
            'Visites des habitats avec un guide (gratuit)' => 'Plongez dans l\'univers fascinant des animaux grâce à nos visites guidées gratuites.'
        ];

        return $this->render('home/services.html.twig', [
            'services' => $services,
        ]);
    }

    #[Route('/habitats', name: 'app_habitats')]
    public function habitats(): Response
    {
        // Utilisation de l'injection du repository pour récupérer les habitats
        $habitats = $this->habitatRepository->findAll();

        return $this->render('home/habitats.html.twig', [
            'habitats' => $habitats,
        ]);
    }

    #[Route('/habitat/{id}', name: 'habitat_detail')]
    public function habitatDetail(Habitat $habitat): Response
    {
        // Initialisation des tableaux de nourriture et grammages
        $nourritures = [
            'Simba' => ['Viande'],
            'Hero' => ['Poissons'],
            'Raja' => ['Viande'],
            'Shelly' => ['Plantes aquatiques'],
            'Ziggy' => ['Herbe'],
            'Lynxie' => ['Petits mammifères'],
            'Grace' => ['Herbes'],
            'Kong' => ['Fruits'],
            'Shadow' => ['Viande'],
            'Kiki' => ['Fruits'],
            'Coco' => ['Herbes'],
            'Blaze' => ['Viande'],
            'Snap' => ['Poissons'],
            'Hoppy' => ['Insectes'],
            'Iris' => ['Poissons'],
        ];

        $grammages = [
            'Simba' => '5.0 kg',
            'Hero' => '1.8 kg',
            'Raja' => '6.0 kg',
            'Shelly' => '0.8 kg',
            'Ziggy' => '4.0 kg',
            'Lynxie' => '2.7 kg',
            'Grace' => '3.5 kg',
            'Kong' => '3.8 kg',
            'Shadow' => '4.5 kg',
            'Kiki' => '2.5 kg',
            'Coco' => '3.2 kg',
            'Blaze' => '5.5 kg',
            'Snap' => '8.0 kg',
            'Hoppy' => '0.2 kg',
            'Iris' => '1.5 kg',
        ];

        // Tableau des détails des animaux
        $animauxDetails = [];

        // Parcours des animaux associés à l'habitat
        foreach ($habitat->getAnimaux() as $animal) {
            $nourriture = implode(', ', $nourritures[$animal->getPrenom()] ?? ['Non spécifiée']);
            $grammage = $grammages[$animal->getPrenom()] ?? 'Non spécifié';

            // On récupère les données vétérinaires si disponibles
            $rapportVeterinaire = $animal->getRapportVeterinaire();
            $dateDePassage = $rapportVeterinaire ? $rapportVeterinaire->getDate()->format('Y-m-d') : 'Non spécifiée';
            $avisVeterinaire = $rapportVeterinaire ? $rapportVeterinaire->getDetail() : 'Non disponible';

            // Ajout de la clé "views"
            $views = $animal->getViews();  // Supposons que chaque animal a un nombre de vues

            $animauxDetails[] = [
                'id' => $animal->getId(),
                'prenom' => $animal->getPrenom(),
                'etat' => $animal->getEtat(),
                'nourriture' => $nourriture,
                'grammage' => $grammage,
                'dateDePassage' => $dateDePassage,
                'avisVeterinaire' => $avisVeterinaire,
                'image' => $animal->getImage(),
                'views' => $views,  // Ajout de la clé "views"
            ];
        }

        return $this->render('habitat/detail.html.twig', [
            'habitat' => $habitat,
            'animaux' => $animauxDetails,
        ]);
    }

    #[Route('/contacts', name: 'app_contact')]
    public function contacts(): Response
    {
        $contacts = [
            ['name' => 'Jean-Marie', 'email' => 'jean.marie@example.com', 'phone' => '123-456-7890', 'mission' => 'Intendant'],
            ['name' => 'Frank Herve', 'email' => 'frank.herve@example.com', 'phone' => '987-654-3210', 'mission' => 'Coordonnateur'],
            ['name' => 'Albert Einstein', 'email' => 'albert.einstein@example.com', 'phone' => '456-789-1234', 'mission' => 'Directeur'],
        ];

        return $this->render('home/contact.html.twig', [
            'contacts' => $contacts,
        ]);
    }
   
  

    // Route pour le tableau de bord Vétérinaire
    #[Route('/veterinaire/dashboard', name: 'veterinaire_dashboard')]
    public function veterinaireDashboard(): Response
    {
    return $this->render('veterinaire_dashboard.html.twig');  
    }

    // Route pour le tableau de bord Employé
    #[Route('/employe/dashboard', name: 'employe_dashboard')]
    public function employeDashboard(): Response
    {
    return $this->render('employe_dashboard.html.twig');  
    }
    //Route pour la connexion
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_service_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/admin/user/create', name: 'admin_user_create')]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
    
        // Création du formulaire
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Employé' => 'ROLE_EMPLOYE',
                    'Vétérinaire' => 'ROLE_VETERINAIRE',
                ],
                'expanded' => false,
                'multiple' => true,
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );
    
            $this->entityManager->persist($user);
            $this->entityManager->flush();
    
            $this->addFlash('success', 'Utilisateur créé avec succès.');
    
            return $this->redirectToRoute('admin_dashboard');
        }
    
        // Passer l'objet 'user' en plus du formulaire au template
        return $this->render('admin/user_create.html.twig', [
            'form' => $form->createView(),
            'user' => $user,  // Passer l'utilisateur au template
        ]);
    }
    #[Route('/admin/users', name: 'user_list')]
    public function listUsers(EntityManagerInterface $em): Response
    {
        // Récupérer tous les utilisateurs
        $users = $em->getRepository(User::class)->findAll();
    
        // Passer les utilisateurs au template
        return $this->render('admin/user_list.html.twig', [
            'users' => $users,  // Passer la liste des utilisateurs
        ]);
    }
    #[Route('/edit/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $em): Response
    {
        // Vérifie si on tente de modifier un compte administrateur (avant de modifier quoi que ce soit)
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
    
            // Sauvegarde les modifications dans la base de données
            $em->persist($user);
            $em->flush();
    
            // Ajoute un message flash pour informer du succès de l'opération
            $this->addFlash('success', 'Les rôles de l\'utilisateur ont été mis à jour avec succès.');
    
            // Redirige vers la liste des utilisateurs après la mise à jour
            return $this->redirectToRoute('user_list');
        }
    
        // Retourne la vue avec le formulaire et l'utilisateur
        return $this->render('admin/user_edit.html.twig', [  // Chemin corrigé pour le template
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    

        

    #[Route('/admin/user/notify/{id}', name: 'admin_user_notify')]
     public function notifyUser(User $user, MailerInterface $mailer): Response
     {
    // Création de l'email avec l'email dynamique de l'utilisateur
    $email = (new Email())
        ->from('josé@aecadia.com')  // Adresse de l'expéditeur
        ->to($user->getEmail())     // Utilisation de l'email dynamique de l'utilisateur
        ->subject('Votre compte a été créé')
        ->html(
            '<p>Bonjour ' . $user->getPrenom() . ' ' . $user->getNom() . ',</p>' .  // Ajout du nom et prénom de l'utilisateur
            '<p>Votre compte a été créé avec succès. Voici votre nom d\'utilisateur :</p>' .
            '<p><strong>' . $user->getUsername() . '</strong></p>' .   // Affichage du username
            '<p>Veuillez contacter un administrateur pour obtenir votre mot de passe.</p>'
        );

    // Envoi de l'email
    $mailer->send($email);

    // Ajouter un message flash de succès
    $this->addFlash('success', 'Notification envoyée à l\'utilisateur.');

    // Redirection vers le tableau de bord administrateur
    return $this->redirectToRoute('admin_dashboard');
}

}
