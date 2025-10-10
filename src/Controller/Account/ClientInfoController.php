<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientInfoController extends AbstractController
{
    #[Route('compte/client/info', name: 'app_client_info')]
    public function index(): Response
    {
        $name= 'a';
        
        return $this->render('account/client_info/index.html.twig', [
            'name'=>$name 
        ]);
    }
}
