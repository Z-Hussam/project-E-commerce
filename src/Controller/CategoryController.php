<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $category  = $categoryRepository->findOneBy(['slug' => $slug]);
        if (!$category) {
            return  $this->redirectToRoute('app_home');
        }
        return $this->render('category/index.html.twig', [
            'category' => $category,
            'categories'=>$categories
        ]);
    }
}
