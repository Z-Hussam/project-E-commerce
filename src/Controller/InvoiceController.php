<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    /*
     * Impressino facture PDF pour un utlisateur connecté
     * Vérification de la commande pour un utlisateur donné 
     */
    #[Route('/compte/facture/{id_order}', name: 'app_invoice_customer')]
    public function printForCustomer(OrderRepository $orderRepository, $id_order): Response
    {
        // Vérification de l'objet commande s'il Existe 
        $order = $orderRepository->findOneById($id_order);
        if (!$order) {
            return $this->redirectToRoute('app_account');
        }
        // Vérification si la commande correspond à l'utilisateur en cours 
        if ($order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account');
        }
        $dompdf = new Dompdf();

        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // Exporter le PDF généré vers le navigateur
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);

        exit();
    }
    /*
     * Impressino facture PDF pour un adminstrateur
     * Vérification de la commande pour  
     */
    #[Route('/admin/facture/{id_order}', name: 'app_invoice_admin')]
    public function printForAdmin(OrderRepository $orderRepository, $id_order): Response
    {
        // Vérification de l'objet commande s'il Existe 
        $order = $orderRepository->findOneById($id_order);
        if (!$order) {
            return $this->redirectToRoute('admin');
        }
     
        $dompdf = new Dompdf();

        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // Exporter le PDF généré vers le navigateur
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);

        exit();
    }
}
