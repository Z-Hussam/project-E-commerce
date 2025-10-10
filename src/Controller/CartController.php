<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    // On ajoute motif comme argument pour l'utiliser en condition selon sa valeur soit pour afficher une message 
    // aprés une rédirection  vers le panier en suite d'une annulation du paiment ou siot pour....
    // et on le met par défaut à null  pour acceder au panier s'il sa valeur est vide 
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: ['motif' => null])]
    public function index(Cart $cart, $motif): Response
    {
        if ($motif == 'annulation') {
            $this->addFlash(
                'info text-center',
                'Paiment annulé : Vous pouvez mettre à jour votre panier et votre commande.'
            );
        }
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart()
        ]);
    }
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, ProductRepository $productRepository, Cart $cart, Request $request): Response
    {
        $product = $productRepository->find($id);
        $cart->add($product);
        $this->addFlash('info text-center', 'Votre produit a été ajouté au panier ');
        // $request->headers->get('referer') nous permet d'obtenir la derniere page (URL) qui était visité  
        return $this->redirect($request->headers->get('referer'));
    }
    #[Route('/cart/minus/{id}', name: 'app_cart_minus')]
    public function minus($id, Cart $cart,): Response
    {

        $cart->decrease($id);
        $this->addFlash('info text-center', 'Votre produit a été supprimé au panier ');
        return $this->redirectToRoute('app_cart');
    }
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart)
    {
        $cart->remove();
        return $this->redirectToRoute('app_home');
    }
}
