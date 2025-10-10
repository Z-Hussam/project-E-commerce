<?php

namespace App\Controller\Account;

use App\Classes\Cart;
use App\Entity\Addresse;
use App\Form\AddresseUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddresseController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }


    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function index()
    {
        return $this->render('account/addresse/index.html.twig');
    }



    //~ C'est la même fonction pour ajouter ou modifier une adresse
    #[Route('/compte/adresses/ajouter/{id}', name: 'app_account_addresses_form', defaults: ['id' => null])]
    public function form(Request $request, $id, Cart $cart)
    {
        /* Si on modifie une adresse on ajouter son id dans l'url ensuit on vérifie dans cette function si l'id exesit pour récuperer l'adresse de BDD pour la modifier*/
        $repository = $this->em->getRepository(Addresse::class);

        if ($id) {
            $addresse = $repository->find($id);

            /*pour vérifier que l'adresse qu'on est en train de la modifier corospande à l'utilisateur en cours  */
            if (!$addresse or $addresse->getUser() != $this->getUser()) {

                return $this->redirectToRoute('app_account_addresses');
            }
            /* Si on trouve pas l'id ça veut dire qu'on est en train de sauvegarder une nouvelle adresse */
        } else {

            $addresse = new Addresse();
            $addresse->setUser($this->getUser());
        }

        $form = $this->createForm(AddresseUserType::class, $addresse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($addresse);
            $this->em->flush();

            $this->addFlash('success', 'Votre adresse est correctement enregistré.');

            // Si le panier n'est pas vide rediger vers la page commande
            if ($cart->fullQuantity() > 0) {
                return $this->redirectToRoute('app_order');
            }
            return $this->redirectToRoute('app_account_addresses');
        }

        return $this->render('account/addresse/from.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
    #[Route('/compte/adresse/supprimer/{id}', name: 'app_account_addresse_delete')]
    public function delete($id)
    {
        $addresse = $this->em->getRepository(Addresse::class)->find($id);

        if (!$addresse or $addresse->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_addresses');
        }

        $this->em->remove($addresse);
        $this->em->flush();

        $this->addFlash('success', 'Votre adresse est correctement supprimé.');

        return $this->redirectToRoute('app_account_addresses');
    }
}
