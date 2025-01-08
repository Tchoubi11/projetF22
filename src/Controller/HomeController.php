<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
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
            'Visite du Zoo en petit train',
            'Visites des habitats avec un guide (gratuit)'
        ];
       

        return $this->render('home/index.html.twig', [
            'reviews' => $reviews,
            'habitats' => $habitats,
            'services' => $services,
        ]);
    }
}

