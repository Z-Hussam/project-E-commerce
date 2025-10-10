<?php

namespace App\Controller;

use App\Classes\Search;
use App\Form\TestType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $search = new Search();
        $form = $this->createForm(TestType::class, $search);
        $form->handleRequest($request);
        $allProducts = $productRepository->findAll();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $filteredProducts = $productRepository->getFilterdProducts($search);
        } else {
            $filteredProducts = $allProducts;
        }
        
        

        return $this->render('test/index.html.twig', [
            'form' => $form->createView(),
            'products' => $filteredProducts
        ]);
    }
}
