<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FAQController extends AbstractController
{
    public function __construct
    (
        private EntityManagerInterface $em,
        private QuestionsRepository $questionsRepository
    )
    {}
    #[Route('/f/a/q', name: 'app_f_a_q')]
    public function index(Request $request): Response
    {
        $questions = new Questions();
        $form = $this->createForm(QuestionsType::class,$questions);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($questions);
            $this->em->flush();
        }
        
         $askedQuestions = $this->questionsRepository->findAll();

        
        return $this->render('faq/index.html.twig', [
            'form'=>$form->createView(),
            'askedQuestions'=> $askedQuestions    
        ]);
    }
}
