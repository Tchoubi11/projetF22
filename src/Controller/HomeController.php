<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Avis;
use App\Entity\Habitat;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\HabitatRepository;
use Symfony\Component\HttpFoundation\RedirectResponse; 



class HomeController extends AbstractController
{
    private $habitatRepository;
    private $avisRepository;
    
    public function __construct(
        HabitatRepository $habitatRepository,
        AvisRepository $avisRepository,   
    ) {
        $this->habitatRepository = $habitatRepository;
        $this->avisRepository = $avisRepository;
    }
    
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        // Récupération des avis visibles
        $avisVisibles = $this->avisRepository->findBy(['isVisible' => true]);

        // Création d' un nouvel avis 
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setVisible(false); // avis est invisible par défaut
            $this->avisRepository->save($avis, true);

            $this->addFlash('success', 'Votre avis a été soumis pour validation.');
            return $this->redirectToRoute('app_home');
        }

        // Contenu statique pour les habitats et services
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
         ['name' => 'Restauration', 'icon' => 'fas fa-utensils'],
         ['name' => 'Visite du zoo en petit train', 'icon' => 'fas fa-train'],
         ['name' => 'Visites des habitats avec un guide (gratuit)', 'icon' => 'fas fa-users']
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
        // Logique pour afficher les services
        $services = [
            'Restauration' => [
                'description' => 'Dégustez une variété de plats savoureux dans nos restaurants situés au cœur du zoo.',
                'image' => 'uploads/images/679b7a2c9f1a1.jpg' 
            ],
            'Visite du zoo en petit train' => [
                'description' => 'Explorez tout le zoo sans effort grâce à notre petit train.',
                'image' => 'uploads/images/679b840a3c0f3.jpg'
            ],
            'Visites des habitats avec un guide (gratuit)' => [
                'description' => 'Plongez dans l\'univers fascinant des animaux grâce à nos visites guidées gratuites.',
                'image' => 'uploads/images/679b8381509c1.jpg'
            ]
        ];
    
        // Rendu du template avec les services
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
    
            // Récupération du dernier rapport vétérinaire
            $rapportsVeterinaires = $animal->getRapportsVeterinaires();
            if (!$rapportsVeterinaires->isEmpty()) {
                $dernierRapport = $rapportsVeterinaires->last(); 
                $dateDePassage = $dernierRapport->getDate()->format('Y-m-d');
                $avisVeterinaire = $dernierRapport->getDetail();
            } else {
                $dateDePassage = 'Non spécifiée';
                $avisVeterinaire = 'Non disponible';
            }

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

    
    
   
    #[Route('/logout', name: 'app_logout')]
    public function logout(Request $request): RedirectResponse
    {
    $session = $request->getSession();
    $session->invalidate();  
    
    return $this->redirectToRoute('app_home');
    }

}
