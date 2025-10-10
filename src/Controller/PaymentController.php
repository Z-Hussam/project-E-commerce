<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{

    #[Route('/commande/payment/{order_id}', name: 'app_payment')]
    public function index($order_id, OrderRepository $orderRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Récuperer l'order seulement si l'id et lutilisateur correspondent à l'id et lutilisateur en BBD
        // pour qu'il ne soit pas possible de modifier l'id dans l'url et récupérer la commande d'autre utilisateur 
        $order = $orderRepository->findOneBy([
            'id' => $order_id,
            'user' => $this->getUser()
        ]);

        // si la commande ne corropspand pas rederiger vers la page d'acceuille 
        if (!$order) {
            return $this->redirectToRoute('app_page_acceuille');
        }
        foreach ($order->getOrederDetails() as $product) {

            $product_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => number_format(($product->getProductPriceWt() * 100), 0, '', ''),
                    'product_data' => [
                        'name' => $product->getproductName(),
                        'images' => [
                            $_ENV['DOMAIN'] . '/uploads/' . $product->getProductIllustration()
                        ]
                    ]
                ],
                'quantity' => $product->getProductQuantity(),
            ];
        }
        //Ajouter le transporteur 
        $product_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => number_format($order->getcarrierPrice() * 100, 0, '', ''),
                'product_data' => [
                    'name' => 'Transporteur : ' . $order->getcarrierName(),
                ]
            ],
            'quantity' => $product->getProductQuantity(),
        ];

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'line_items' => [[
                $product_stripe
            ]],
            'mode' => 'payment',
            'success_url' =>  $_ENV['DOMAIN'] . '/commande/successe/{CHECKOUT_SESSION_ID}',
            'cancel_url' =>   $_ENV['DOMAIN'] . '/mon-panier/annulation',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManagerInterface->flush();
        return $this->redirect($checkout_session->url);
    }

    #[Route('/commande/successe/{stripe_session_id}', name: 'app_payment_success')]
    public function success($stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $entityManagerInterface, Cart $cart)
    {

        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
        if ($order->getState() == 1) {
            $order->setState(2);
            $cart->remove();
            $entityManagerInterface->flush();
        }
        return $this->render('paiment/success.html.twig', [
            'order' => $order,
        ]);
    }
}
