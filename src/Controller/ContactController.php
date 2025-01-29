<?php


namespace App\Controller;

use App\Form\ContactType;
use App\Entity\ContactRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    #[Route('/contacts', name: 'app_contact')]
    public function contacts(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $contactRequest = new ContactRequest();
        $form = $this->createForm(ContactType::class, $contactRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement de la demande dans la base de données
            $entityManager->persist($contactRequest);
            $entityManager->flush();

            // Envoi de l'email au zoo
            $email = (new Email())
              ->from($contactRequest->getEmail())
              ->to('zoo@earcadia.com')  
              ->subject('Demande de contact')
              ->html(
                '<p>Titre : ' . $contactRequest->getTitre() . '</p>' .
                '<p>Description : ' . $contactRequest->getDescription() . '</p>' .
                '<p>Contact : ' . $contactRequest->getEmail() . '</p>'
            )
              // Ajout de Reply-To pour permettre à l'employé du zoo de répondre directement à l'utilisateur
              ->replyTo($contactRequest->getEmail());
        

            try {
                $mailer->send($email);
                $this->addFlash('success', 'Votre demande a été envoyée avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Il y a eu un problème lors de l\'envoi de votre demande.');
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
