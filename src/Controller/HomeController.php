<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
/**
 * page d'accueil
 * @return Response
 * 
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

    
     #[Route("/services", name:"app_services")]
     
    public function services(): Response
    {
       
        $services = [
            'Restauration'=>'Dégustez une variété de plats savoureux dans nos restaurants situés au cœur du zoo. Nous proposons des options pour toute la famille, y compris des repas végétariens et adaptés aux enfants.',
            'Visite du zoo en petit train'=>'Explorez tout le zoo sans effort grâce à notre petit train. Idéal pour les familles et les visiteurs souhaitant profiter d\'une vue d\'ensemble du parc tout en se relaxant. ',
            'Visites des habitats avec un guide (gratuit)'=>'Plongez dans l\'univers fascinant des animaux grâce à nos visites guidées gratuites. Découvrez les secrets des habitats de la savane, de la jungle et des marais .'
        ];
        $names=['Restauration','Visite du Zoo en petit train','Visites des habitats avec un guide (gratuit)'];
        $descriptions=['Dégustez une variété de plats savoureux dans nos restaurants situés au cœur du zoo. Nous proposons des options pour toute la famille, y compris des repas végétariens et adaptés aux enfants.','Explorez tout le zoo sans effort grâce à notre petit train. Idéal pour les familles et les visiteurs souhaitant profiter d\'une vue d\'ensemble du parc tout en se relaxant. ','Plongez dans l\'univers fascinant des animaux grâce à nos visites guidées gratuites. Découvrez les secrets des habitats de la savane, de la jungle et des marais .'];

        return $this->render('home/services.html.twig',[
         'services'=>$services,
         'names'=>$names,
         'descriptions'=>$descriptions,
        ]);

    }

    #[Route("/habitats",name:"app_habitats")]
    public function Habitats():Response
    {
        $habitats = [
            'Savane' => ['Lions', 'Girafes', 'Zèbres'],
            'Jungle' => ['Singes', 'Tigres', 'Léopards'],
            'Marais' => ['Couguar', 'Renard gris']
        ];
        return $this->render('home/habitats.html.twig',[
        'habitats' => $habitats,
        ]);
    }

    #[Route("/contacts",name:"app_contact")]
    public function Contact():Response
    {
        $contacts = [
            ['name' => 'Jean-Marie', 'email' => 'jean.marie@example.com', 'phone' => '123-456-7890','mission'=>'Intendant'],
            ['name' => 'Frank Herve', 'email' => 'frank.herve@example.com', 'phone' => '987-654-3210','mission'=>'Cordonnateur'],
            ['name' => 'Albert Einstein', 'email' => 'albert.einstein@example.com', 'phone' => '456-789-1234','mission'=>'Directeur '],
        ];
        return $this->render('home/contact.html.twig',[
           'contacts' => $contacts,
        ]);
    }
 
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

    if ($this->getUser()) {
        // Redirige vers la page d'accueil si déjà connecté
        return $this->redirectToRoute('app_home');
    }
        // Récupère l'erreur de connexion, s'il y en a
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
   }
   #[Route('/logout', name: 'app_logout', methods: ['GET'])]
   public function logout(): void
   {
       // Je la laisse vide, elle sera interceptée par Symfony pour gérer la déconnexion
   }
  
}
