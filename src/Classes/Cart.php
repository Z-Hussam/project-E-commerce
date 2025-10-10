<?php

namespace App\Classes;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * 1- Appeler la session de symfony | RequestStack $requestStack->getSession()
     * 2- Ajouter un quantité 1 à mon produit 
     * 3- Créer ma session cart
     */
    /**
     * add
     * Fonction permet d'ajouter un produit au panier 
     */
    public function add($product)
    {
        $session = $this->requestStack->getSession();
        //récupérer le panier en cour
        $cart = $this->getCart();
        // $cart = [];
        //Ajouter un nouvel objet produit et sa quantité au panier pour le transmettre à la session comme valeur
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' =>  $cart[$product->getId()]['qty'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }
        //créer la session et lui donner le nom cart grâce à la methode set qui prend 2 arguments key => value
        //et lui passer le panier avec les nouveaux objets
        $this->requestStack->getSession()->set('cart', $cart);
    }
    /**
     * remove
     * Fonction permet de supprimer totalement le panier 
     */
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
    /**
     * decrease
     * Fonction permet la suppression d'une quantité d'un produit au panier 
     */
    public function decrease($id)
    {
        $cart = $this->getCart();
        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        } else {
            unset($cart[$id]);
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /**
     * getCart
     * Fonction retourne le panier
     */
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
    /**
     * fullQuantity
     * fonction retourne le nombre total de produits au panier 
     */
    public function fullQuantity()
    {
        $quantity = 0;
        $cart = $this->getCart();
        if (!isset($cart)) {
            return $quantity;
        }
        
        foreach ($cart as $product) {
            
            $quantity += $product['qty'];
        }
        return $quantity;
    }

    /**
     * getTotalWt
     * Fonction retourne le prix total des produis au  panier 
     */
    public function getTotalWt()
    {
        $price = 0;
        $cart = $this->getCart();
        if (!isset($cart)) {
            return $price;
        }
        foreach ($cart as $product) {
            $price += $product['object']->getPriceWt() * $product['qty'];
        }
        return $price;
    }
}
