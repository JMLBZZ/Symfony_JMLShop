<?php

namespace App\Controller\Account;

use App\Class\Basket;
use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class AddressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/addresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }

    #[Route('/account/address/add/{id}', name: 'app_account_address_form', defaults:["id" =>null])]
    public function form(Request $request, $id, AddressRepository $addressRepository, Basket $basket): Response
    {
        if($id){//si id existe, alors...
            $address = $addressRepository->findOneById($id);//...modifie le formulaire
            if(!$address OR $address->getUser() != $this->getUser()){//Si l'adresse n'existe pas, ou s'il est différent de l'utilisateur en cours, alors...
                return $this->redirectToRoute("app_account_addresses");
            } 

        }else{//...sinon, tu crée l'adresse
            $address = new Address();
            $address->setUser($this->getUser());//lobjet address, utilise la méthode setUser de l'entité Adress sur l'utilisateur connecté
        }

        

        $form = $this->createForm(AddressUserType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash(
                'success',//type de notif (couleur success bootstrap)
                'Adresse enregistrée !'//message de la notif
            );

            if($basket->totalQty() > 0){//si le panier est sup à 0, alors...
                return $this->redirectToRoute("app_order");//...redirige vers la page de commande
            }

            return $this->redirectToRoute("app_account_addresses");
        }

        return $this->render('account/address/form.html.twig',[
            "addressForm" => $form
        ]);
    }

    #[Route('/account/addresses/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);
        if(!$address OR $address->getUser() != $this->getUser()){
            return $this->redirectToRoute("app_account_addresses");
        }

        $this->addFlash(
            'success',//type de notif (couleur success bootstrap)
            'Adresse supprimée !'//message de la notif
        );

        $this->entityManager->remove($address);//communication à la bdd pour supprimer
        $this->entityManager->flush();//on enregistre dans la bdd
        return $this->redirectToRoute("app_account_addresses");
    }

}