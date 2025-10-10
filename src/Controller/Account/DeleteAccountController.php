<?php

namespace App\Controller\Account;

use App\Repository\AddresseRepository;
use App\Repository\OrderRepository;
use App\Repository\OrederDetailRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteAccountController extends AbstractController
{
    public function __construct
    (
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private AddresseRepository $addresseRepository,
        private OrderRepository $orderRepository,
        private OrederDetailRepository $orderDetailsRepository,
    )
    {}
    
    #[Route('/delete/account', name: 'app_delete_account')]
    public function index(): Response
    {
        
        
        return $this->render('account/delete_account/index.html.twig', [
            'controller_name' => 'DeleteAccountController',
        ]);
    }
     #[Route('/delete/accoutn/{id}', name:'app_deleteAccount')]
    public function delete(Session $session,$id)
    {                           
       
        $user = $this->userRepository->find($id);
        if ($user !== null) {
            
        // $this->redirect('app_logout');
        // Get user to remover from database by its id
        // $user_to_remove = $this->userRepository->findOneBy(['id'=>$id]);

        // Retrive the user's addresses from data base to be able to delete them  from database befor deleting user
        // $addresses_to_remove = $this->addresseRepository->findBy(['user'=>$user_to_remove]);
        $addresses_to_remove = $this->addresseRepository->findBy(['user'=>$user]);
        
        // Looping throw the array of user's addresses to delete eachone of them from the database
        foreach($addresses_to_remove as $addresse)
        {
            $this->em->remove($addresse);
            $this->em->flush();
        }
    
        // Retriving the orders of this user to be able to delete theme
        $orders_to_remove = $this->orderRepository->findBy(['user'=> $user]);

        
        
        // $ordersDetails_to_remove =[];
        foreach($orders_to_remove as $order)
        {
        $ordersDetails_to_remove= $this->orderDetailsRepository->findBy(['myOrder'=>$order]);
            foreach($ordersDetails_to_remove as $orderDetail)
            {
                $this->em->remove($orderDetail);
                $this->em->flush();
            }
           
            
            $this->em->remove($order);
            $this->em->flush();
        }
        
        
        // dd($user);

        
        // return $this->redirectToRoute('app_logout');    
        $this->em->remove($user);
        $this->em->flush();

        $this->addFlash('success', 'Votre compte est supprimé, nous sommes triste de vous voir paritir notre chère client client qui achète beaucoup de notre boutique hahahahahahahahahahah .');


        
    }
        //   return  $this->redirect('app_logout');
    return $this->redirectToRoute('app_login');    
    // return $this->render('account/delete_account/deleted_account.html.twig');
}
}
