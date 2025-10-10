<?php

namespace App\Controller;

use App\Entity\Comment as EntityComment;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


class CommentsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private CommentRepository $comment_repository
    ) {}

    //Ajouter une commentaire 
    #[Route('/comments/ajouter/{productId}', name: 'app_comments')]
    public function index(#[CurrentUser] User $user, $productId, Request $request): Response
    {

        $userOrders = $user->getOrders();

        // Créer un tableau pour enregistrer les id de prduits achétés par cet utilisateur 
        $productIdentifires = [];

        // looper dans lobjet oder de cet utilisateur pour accesder à l'objet orderDetails
        foreach ($userOrders as $order) {
            // looper dans l'objet order Details pour recuperer les product Identifier des chaque orderDetail objet et les enregiterer dans un tabl
            foreach ($order->getOrederDetails() as $orederDetail) {
                $productIdentifires[] = $orederDetail->getProductIdentifier();
            }
        };

        $product = $this->productRepository->findOneBy(['id' => $productId]);

        // Si cet identifiant de produit ne figure pas dans productIdentifiers => Produits achetés par cet utilisateur, il ne pourra pas ajouter de commentaire. 
        if (!in_array($productId, $productIdentifires)) {
            $this->addFlash('warning', "Vous ne pouvez pas ajouter d'avis car vous n'avez jamais acheté ce produit auparavant.");
            return $this->redirectToRoute('app_product', ['slug' => $product->getSlug()]);
        }

        
        $comment = new EntityComment();
        $form = $this->createForm(CommentType::class, $comment);

        $is_comment_exist = $this->comment_repository->findBy([
            'author' => $user,
            'product' => $productId
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($is_comment_exist && count($is_comment_exist) > 0 && count($is_comment_exist) < 2) {
                $this->addFlash('success', 'vous avez déja commenté , vous pouvez modififer votre commntaire si vous souhaitez');
                return $this->redirectToRoute('app_product', ['slug' => $product->getSlug()]);
            } else {
                $comment->setAuthor($user);
                $comment->setProduct($product);
                $comment->setContent($form->getData()->getContent());
                $this->em->persist($comment);
                $this->em->flush();
                return $this->redirectToRoute('app_product', ['slug' => $product->getSlug()]);
            }
        }
        return $this->render('comments/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Modifier une commentaire
    #[Route('/comments/{commentId}', name: 'app_comments_modify')]
    public function modifyComment($commentId, #[CurrentUser] User $user, Request $request)
    {
        $comment = $this->comment_repository->find($commentId);
        $product = $comment->getProduct();
        if ($comment->getAuthor()  != $user) {
            $this->addFlash('danger', "Vous ne pouvez pas modifier ce commentaire si vous n'êtes pas l'auteur de ce commentaire.");
             return $this->redirectToRoute('app_product', ['slug' => $product->getSlug()]);
        }

        

        if (!$comment or $comment->getAuthor() != $user) {
            return $this->redirectToRoute('app_product', ['slug' => $product->getSlug()]);
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire est correctement modifié.');
            return $this->redirectToRoute('app_product', ['slug' => $product->getSlug()]);
        }

        return $this->render('comments/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/comment/supprimer/{commentId}', name: "app_comment_delete")]
    public function deleteComment($commentId, #[CurrentUser] User $user)
    {
        $comment = $this->comment_repository->find($commentId);
        $product = $comment->getProduct();

        if (!$comment or $comment->getAuthor() != $user) {
            $this->addFlash('danger', "Vous ne pouvez pas suppreimer ce commentaire si vous n'êtes pas l'auteur de ce commentaire.");
            return $this->redirectToRoute('app_product', ['slug' => $product->getSlug()]);
        }
        
        $this->em->remove($comment);
        $this->em->flush();

        $this->addFlash('success', 'Votre comment est correctement supprimé.');
        return $this->redirectToRoute('app_product', ['slug' => $product->getSlug()]);
    }
}
