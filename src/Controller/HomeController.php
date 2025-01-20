<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\HabitatRepository;
use App\Repository\AnimalRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Habitat;
use App\Entity\Animal;



class HomeController extends AbstractController
{
    /**
     * Page d'accueil
     * @return Response
     */
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
    public function habitats(HabitatRepository $habitatRepository): Response
    {
        $habitats = $habitatRepository->findAll();

        return $this->render('home/habitats.html.twig', [
            'habitats' => $habitats,
        ]);
    }
    //Pour afficher la liste des habitats
    #[Route('/habitat/{id}', name: 'habitat_detail')]
    public function habitatDetail(Habitat $habitat): Response
    {
        return $this->render('habitat/detail.html.twig', [
            'habitat' => $habitat,
            'animaux' => $habitat->getAnimaux(),
        ]);
    }
    #[Route('/animal/{id}', name: 'animal_details')]
public function animalDetails(Animal $animal): JsonResponse
{
    // Définir des valeurs possibles pour chaque propriété
    $nourritures = [
        'Lion' => ['Viande'],
        'Héron' => ['Poissons'],
        'Tigre' => ['Viande'],
        'Tortue' => ['Plantes aquatiques'],
        'Zebre' => ['Herbe'],
        'Lynx' => ['Petits mamifères'],
        'Gazelle'=>[''],
        'Gorille'=>['Fruits'],
        'Léopard'=>['Viande'],
        'Singe'=>['Fruits'],
        'Capibara'=>['Herbes'],
        'Jaguar'=>['Viande'],
        'Crocodile'=>['Poissons'],
        'Grenouille'=>['Insectes'],
        'Ibis'=>['Poissons'],
    ];

    $grammages = [
        'Lion' => ['150 kg'],
        'Héron' => ['10 kg'],
        'Tigre' => ['150 kg'],
        'Tortue' => ['8kg'],
        'Zebre' => ['100 kg'],
        'Lynx' => ['70 kg'],
        'Gazelle'=>['65 kg'],
        'Gorille'=>['130 kg'],
        'Léopard'=>['80 kg'],
        'Singe'=>['20 kg'],
        'Capibara'=>['35 kg'],
        'Jaguar'=>['80 kg'],
        'Crocodile'=>['90 kg'],
        'Grenouille'=>['1 kg'],
        'Ibis'=>['10 kg'],
    ];

    $animalName = $animal->getPrenom();

    if (!array_key_exists($animalName, $nourritures) || !array_key_exists($animalName, $grammages)) {
        return new JsonResponse(['error' => 'Animal non trouvé dans les données spécifiées'], Response::HTTP_NOT_FOUND);
    }
    $nourriture = $nourritures[$animalName][array_rand($nourritures[$animalName])] ?? 'Non spécifié';
    $grammage = $grammages[$animalName][array_rand($grammages[$animalName])] ?? 'Non spécifié';

    // Je recupère la date de passage à partir de l'entité RapportVeterinaire 
    $rapportVeterinaire = $animal->getRapportVeterinaire();
    $dateDePassage = $rapportVeterinaire ? $rapportVeterinaire->getDate()->format('Y-m-d') : 'Non spécifiée';

    $details = [
        'prenom' => $animal->getPrenom(),
        'etat' => $animal->getEtat(),
        'nourriture' => $nourriture,
        'grammage' => $grammage,
        'dateDePassage' => $dateDePassage, 
        'avisVeterinaire' => $animal->getRapportVeterinaire() ? $animal->getRapportVeterinaire()->getDetail() : null,
        'imageUrl' => $this->generateUrl('asset', ['path' => str_replace('public/', '', $animal->getImage())]),
    ];

    return new JsonResponse($details);
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

    
}
