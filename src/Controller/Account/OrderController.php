<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/account/order/{id_order}', name: 'app_account_order')]
    public function index($id_order, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy([
            "id" => $id_order,
            "user" => $this->getUser(),
        ]);

        //Si pas de commande, alors redirige vers la page d'accueil
        if (!$order){
            $this->addFlash(
                'danger',//type de notif (couleur success bootstrap)
                'Aucunes commande enregistré. Veuillez en créer une !'//message de la notif
            );
            return $this->redirectToRoute("app_home");
        };
        
        return $this->render('account/order/index.html.twig', [
            'order' => $order,
        ]);
    }
}
