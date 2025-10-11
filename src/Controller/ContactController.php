<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $email = new Mail();

            $vars = [
                'prenom' => $form->get('prenom')->getData(),
                'nom' =>  $form->get('nom')->getData(),
                'email' =>  $form->get('email')->getData(),
                'content' =>  $form->get('content')->getData(),
            ];

            $email->send(
                'dev-246@outlook.com',
                'Admin',
                'Client-contact',
                'client-contact.html',
                $vars
            );
            $this->addFlash('info text-center my-3', 'Merci de nous contacter ,Notre équipe va vous répondre dans les milleurs délais.');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
