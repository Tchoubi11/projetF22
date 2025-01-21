<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\HabitatRepository;
use App\Repository\AnimalRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Habitat;
use App\Entity\Animal;

class HomeController extends AbstractController
{
    private $habitatRepository;

    // Injecter HabitatRepository via le constructeur
    public function __construct(HabitatRepository $habitatRepository)
    {
        $this->habitatRepository = $habitatRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
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
            'reviews' => $reviews,
            'habitats' => $habitats,
            'services' => $services,
        ]);
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
            'Lion' => ['Viande'],
            'Héron' => ['Poissons'],
            'Tigre' => ['Viande'],
            'Tortue' => ['Plantes aquatiques'],
            'Zèbre' => ['Herbe'],
            'Lynx' => ['Petits mammifères'],
            'Gazelle' => ['Herbes'],
            'Gorille' => ['Fruits'],
            'Léopard' => ['Viande'],
            'Singe' => ['Fruits'],
            'Capibara' => ['Herbes'],
            'Jaguar' => ['Viande'],
            'Crocodile' => ['Poissons'],
            'Grenouille' => ['Insectes'],
            'Ibis' => ['Poissons'],
        ];

        $grammages = [
            'Lion' => '5.0 kg',
            'Héron' => '1.8 kg',
            'Tigre' => '6.0 kg',
            'Tortue' => '0.8 kg',
            'Zèbre' => '4.0 kg',
            'Lynx' => '2.7 kg',
            'Gazelle' => '3.5 kg',
            'Gorille' => '3.8 kg',
            'Léopard' => '4.5 kg',
            'Singe' => '2.5 kg',
            'Capibara' => '3.2 kg',
            'Jaguar' => '5.5 kg',
            'Crocodile' => '8.0 kg',
            'Grenouille' => '0.2 kg',
            'Ibis' => '1.5 kg',
        ];

        // Tableau des détails des animaux
        $animauxDetails = [];

        // Parcours des animaux associés à l'habitat
        foreach ($habitat->getAnimaux() as $animal) {
            $nourriture = implode(', ', $nourritures[$animal->getPrenom()] ?? ['Non spécifiée']);
            $grammage = $grammages[$animal->getPrenom()] ?? 'Non spécifié';

            // Récupérer les données vétérinaires si disponibles
            $rapportVeterinaire = $animal->getRapportVeterinaire();
            $dateDePassage = $rapportVeterinaire ? $rapportVeterinaire->getDate()->format('Y-m-d') : 'Non spécifiée';
            $avisVeterinaire = $rapportVeterinaire ? $rapportVeterinaire->getDetail() : 'Non disponible';

            $animauxDetails[] = [
                'id' => $animal->getId(),
                'prenom' => $animal->getPrenom(),
                'etat' => $animal->getEtat(),
                'nourriture' => $nourriture,
                'grammage' => $grammage,
                'dateDePassage' => $dateDePassage,
                'avisVeterinaire' => $avisVeterinaire,
                'image' => $animal->getImage(),
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

    #[Route('/admin/avis', name: 'admin_avis')]
    public function adminAvis(AvisRepository $avisRepository): Response
    {
        $avisNonVisibles = $avisRepository->findBy(['isVisible' => false]);

        return $this->render('admin/avis.html.twig', [
            'avisNonVisibles' => $avisNonVisibles,
        ]);
    }

    #[Route('/admin/avis/valider/{id}', name: 'admin_avis_valider')]
    public function validerAvis(Avis $avis, AvisRepository $avisRepository): Response
    {
        $avis->setVisible(true);
        $avisRepository->save($avis, true);

        $this->addFlash('success', 'L\'avis a été validé.');

        return $this->redirectToRoute('admin_avis');
    }

    #[Route('/admin/avis/supprimer/{id}', name: 'admin_avis_supprimer')]
    public function supprimerAvis(Avis $avis, AvisRepository $avisRepository): Response
    {
        $avisRepository->remove($avis, true);

        $this->addFlash('success', 'L\'avis a été supprimé.');

        return $this->redirectToRoute('admin_avis');
    }
}
