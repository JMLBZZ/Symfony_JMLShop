<?php

namespace App\Controller;

use App\Class\Basket;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('order/payment/{id_order}', name: 'app_payment')]
    public function index($id_order, OrderRepository $orderRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        Stripe::setApiKey($_ENV["STRIPESECRETKEY"]);

        $order = $orderRepository->findOneBy([//recherche multi-critère par l'id et l'user en cours
            "id" => $id_order,
            "user" => $this->getUser(),
        ]);
        //dd($order);

        //Si pas de commande, alors redirige vers la page d'accueil
        if (!$order){
            $this->addFlash(
                'danger',//type de notif (couleur success bootstrap)
                'Aucunes commande enregistré. Veuillez en créer une !'//message de la notif
            );
            return $this->redirectToRoute("app_home");
        };

        $productsStripe = [];

        foreach ($order->getOrderDetails() as $product){
            $productsStripe[] = [//le crochet vide c'est qu'à chaque fois qu'on rentre dans la boucle, on crée une nouvelle entrée qui s'ajoute à la précédente (évite de supprimer le produit précédent)
                'price_data' => [
                    "currency" => "eur",
                    "unit_amount" => number_format($product->getProductPriceTva() * 100, 0, "", ""),//prix ttc, sans décimale, sans séparateur de décimale, et sans séparateurs de milliers
                    "product_data" =>[
                        "name" => $product->getProductName(),
                        "images" => [
                            $_ENV["DOMAIN"].'/uploads/'.$product->getProductImage(),
                        ]
                    ],
                ],
                'quantity' => $product->getProductQuality(),//Quantity
            ];
        };//fin de boucle

        $productsStripe[] = [
            'price_data' => [
                "currency" => "eur",
                "unit_amount" => number_format($order->getCarrierPrice() * 100, 0, "", ""),//prix ttc, sans décimale, sans séparateur de décimale, et sans séparateurs de milliers
                "product_data" =>["name" => "Transporteur : ".$order->getCarrierName(),],
            ],
            'quantity' => 1,
        ];

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),//autocompletion de l'adresse mail de l'utilisateur
            'line_items' => [[
                # voir la documentation : https://docs.stripe.com/api/checkout/sessions/create
                $productsStripe
            ]],
            'mode' => 'payment',
            'success_url' => $_ENV["DOMAIN"] . 'order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV["DOMAIN"] . 'basket/cancel',
        ]);

        $order->setStripeSessionId($checkout_session->id);//enregistre en bdd l'id stripe de la session
        $entityManagerInterface->flush();

        //header("HTTP/1.1 303 See Other");
        //header("Location: " . $checkout_session->url);

        return $this->redirect($checkout_session->url);//dnas le cas où il y a un problème de symfony, ajouter cette ligne

    }

    // ##################################################################### //
    // ########################## SUCCESS PAYMENT ########################## //
    // ##################################################################### //
    #[Route('order/success/{stripe_session_id}', name: 'app_payment_success')]
    public function success($stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $entityManagerInterface, Basket $basket): Response
    {
        $order = $orderRepository->findOneBy([
            "stripe_session_id" => $stripe_session_id,
            "user" => $this->getUser(),
        ]);

        //Si pas de commande, alors redirige vers la page d'accueil
        if (!$order){
            $this->addFlash(
                'danger',//type de notif (couleur success bootstrap)
                'Accès non autorisé !'//message de la notif
            );
            return $this->redirectToRoute("app_home");
        };

        if($order->getState() == 1){//changement du statut de la commande
            $order->setState(2);//on le passe à payé
            $basket->remove();//puis on vide le panier
            $entityManagerInterface->flush();
        }

        return $this->render('payment/success.html.twig',[
            "order" => $order,
        ]);
    }



}
