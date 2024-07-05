<?php

namespace App\Controller;

use DateTime;
use App\Class\Basket;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    // ##################################################################### //
    // ############## Étape 1 : choix adresse et transporteur ############## //
    // ##################################################################### //
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();
        if(count($addresses) == 0){//si pas d'adresse (collection)
            $this->addFlash(
                'danger',//type de notif (couleur success bootstrap)
                'Aucunes adresse enregistré. Veuillez en créer une !'//message de la notif
            );
            return $this->redirectToRoute("app_account_address_form");
        }


        $form = $this->createForm(OrderType::class, null, [
            "addresses" => $addresses,
            "action" => $this->generateUrl("app_order_summary")
        ]);//2eme paramètre c'est le mapping à une entité, le 3eme paramètre sont les options

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }


    // ##################################################################### //
    // ################### Étape 2 : Récap de la commande ################## //
    // ##################################################################### //
    #[Route('/order/summary', name: 'app_order_summary')]
    public function add(Request $request, Basket $basket, EntityManagerInterface $entityManager): Response
    {
        $addresses = $this->getUser()->getAddresses();

        if($request->getMethod() != "POST"){
            $this->addFlash(
                'danger', // type de notif (couleur success bootstrap)
                'Accès non autorisé !' // message de la notif
            );
            return $this->redirectToRoute("app_basket");
        }
        
        
        $form = $this->createForm(OrderType::class, null, [
            "addresses" => $addresses = $this->getUser()->getAddresses(),
        ]);
        $products = $basket->getBasket();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $addressObject = $form->get("addresses")->getData();

            $address =  $addressObject->getLastName()." ".$addressObject->getFirstName()."<br/>";
                $address .=  $addressObject->getAddress()."<br/>";
                $address .=  $addressObject->getPostal()." ".$addressObject->getCity()."<br/>";
                $address .=  $addressObject->getCountry()."<br/>";
                $address .=  $addressObject->getPhone();

            //dd($basket);


            $order = new Order();

            $order -> setCreatedAt(new DateTime());
            $order -> setState(1);
            $order -> setCarrierName($form->get("carriers")->getdata()->getName());
            $order -> setCarrierPrice($form->get("carriers")->getdata()->getPrice());
            $order -> setDelivery($address);
            $order -> setUser($this->getUser());

            foreach ($products as $product){
                //dd($product);
                $orderDetail = new OrderDetail();

                $orderDetail -> setProductName($product["object"]->getName());
                $orderDetail -> setProductImage($product["object"]->getImage());
                $orderDetail -> setProductPrice($product["object"]->getPrice());
                $orderDetail -> setProductTva($product["object"]->getTva());
                $orderDetail -> setProductQuality($product["qty"]);//c'est la quantité
                $order -> addOrderDetail($orderDetail);
            }

            $entityManager->persist($order);
            $entityManager->flush();
        }


        
        return $this->render('order/summary.html.twig',[
            "choices" => $form->getData(),
            "basket" => $products,
            "totalPriceTva" => $basket->getTotalPriceTva(),
            "order" => $order,
        ]);
    }
}
