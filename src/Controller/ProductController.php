<?php

namespace App\Controller;

use App\Classes\Search;
use App\Form\SearchType;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/nos-produits', name: 'app_products')]
    public function search(Request $request, ProductRepository $productRepository)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $form->isSubmitted() && $form->isValid()
            ?
            $products = $productRepository->getFilterdProducts($search)
            :
            $products = $productRepository->findAll();

        return $this->render('product/products.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product')]
    public function index($slug, ProductRepository $productRepository,CommentRepository $comment_repository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if (!$product) {
            return $this->redirectToRoute('app_home');
        }

        
        $comments=  $product->getComments();
    
        
       
      

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'comments'=>$comments
            
        ]);
    }
}
