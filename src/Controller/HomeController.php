<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\HabitatRepository;
use Symfony\Component\HttpFoundation\Request;
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
    //Pour afficher les détails des habitats
    #[Route('/animal/{id}', name: 'animal_details', methods: ['GET'])]
public function getAnimalDetails(int $id, AnimalRepository $animalRepository): JsonResponse
{
    $animal = $animalRepository->find($id);

    if (!$animal) {
        return new JsonResponse(['error' => 'Animal not found'], Response::HTTP_NOT_FOUND);
    }

    return new JsonResponse([
        'prenom' => $animal->getPrenom(),
        'race' => $animal->getRace() ? $animal->getRace()->getLabel() : null,
        'etat' => $animal->getEtat(),
        'nourriture' => $animal->getNourriture(),
        'grammage' => $animal->getGrammage(),
        'dateDePassage' => $animal->getDateDePassage() ? $animal->getDateDePassage()->format('Y-m-d') : null,
        'rapportVeterinaire' => $animal->getRapportVeterinaire(),
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

    
}
