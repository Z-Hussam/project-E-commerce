<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(Request $request, EntityManagerInterface $em,): Response
    {

        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre compte est créé, veuillez vous conneceter.');

            //Envoi d'un email de confirmation d'inscription 
            $mail = new Mail();
            $vars = [
                'firstname' => $user->getFirstname()
            ];

            $mail->send($user->getUserIdentifier(), $user->getFirstname() . ' ' . $user->getLastname(), 'Bienvenu dans la boutique Shope on line',  'inscription.html', $vars);
            
           
            
            return $this->redirectToRoute('app_login');
        }



        return $this->render('inscription/index.html.twig', [
            'formInscription' => $form->createView()
        ]);
    }
}
