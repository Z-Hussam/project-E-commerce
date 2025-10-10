<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\OrederDetail;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{  /*
    * 1ére étap du tunel d'achat 
    * Choix de l'adresse de livraison et du transporteur  
    */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();
        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_account_addresses_form');
        }
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_summary')
        ]);

        return $this->render('order/index.html.twig', [
            'deliverForm' => $form->createView()
        ]);
    }

    #[Route('/command/recapitulatif', name: 'app_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $em): Response
    {
        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $products = $cart->getCart();

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //Création de la chaîne adresse
            $addresseObject = $form->get('addresses')->getData();
            $addresse  = $addresseObject->getFirstName() . ' ' . $addresseObject->getLastName() . '</br>';
            $addresse .= $addresseObject->getAddresse() . '</br>';
            $addresse .= $addresseObject->getPostal() . ' ' . $addresseObject->getCity() . '</br>';
            $addresse .= $addresseObject->getCountry() . '</br>';
            $addresse .= $addresseObject->getPhone();
            // Préparer les données de la commande pour l'enregistrer dans l'entité Order 
            $order = new Order();
         
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setcarrierName($form->get('carriers')->getData()->getName());
            $order->setcarrierPrice($form->get('carriers')->getData()->getPrice());;
            $order->setdelivery($addresse);

            // Préparer les données du panier  pour l'enregistrer dans l'entité OrderDetail
            foreach ($products as $product) {

                $orderDetail = new OrederDetail();
                $orderDetail->setProductIdentifier($product['object']->getId());
                $orderDetail->setproductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getillustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['qty']);
                $order->addOrederDetail($orderDetail);
            }

            $em->persist($order);
            $em->flush();
        }
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'order' => $order,
            'totalWt' => $cart->getTotalWt()
        ]);
    }
}
