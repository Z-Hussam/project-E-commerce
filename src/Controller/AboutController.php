<?php

namespace App\Controller;

use App\Entity\FAQ;
use App\Entity\Questions;
use App\Form\FAQType;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutController extends AbstractController
{
 

    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
       
        return $this->render('about/index.html.twig',);
    }
}
