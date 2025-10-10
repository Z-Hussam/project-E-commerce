<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishListController extends AbstractController
{
    #[Route('/compte/liste-de-souhait', name: 'app_account_wish_list')]
    public function index(): Response
    {

        return $this->render('account/wish_list/index.html.twig');
    }
    #[Route('/compte/liste-de-souhait/ajouter/{id}', name: 'app_account_wish_list_add')]
    public function add($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        // - Récupérer l'objet du produit souhaité
        $product = $productRepository->findOneById($id);
        // - Si le produit existe, ajouter le produit à la wishlist
        if ($product) {
            $this->getUser()->addWishlist($product);
            $entityManagerInterface->flush();
        }
        $this->addFlash('info text-center', 'Produit ajouté à votre liste de souhait ');
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/compte/liste-de-souhait/supprimer/{id}', name: 'app_account_wish_list_remove')]
    public function remove($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        // - Récupérer l'objet du produit souhaité
        $product = $productRepository->findOneById($id);
        // - Si le produit existe, ajouter le produit à la wishlist
        if ($product) {
            $this->getUser()->removeWishlist($product);
            $entityManagerInterface->flush();
            $this->addFlash('info text-center', 'produit est supprimé de votre lsite de souhait');
        } else {
            $this->addFlash('danger', 'produit introuvable');
        }
        return $this->redirect($request->headers->get('referer'));
    }
}
