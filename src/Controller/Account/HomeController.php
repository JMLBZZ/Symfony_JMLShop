<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([
            "user" => $this->getUser(),
            "state" =>[2,3],//affiche les status 2 (payé) et 3 (expédié)
        ]);

        //dd($orders);
        
        return $this->render('account/index.html.twig',[
            "orders" => $orders
        ]);
    }
}
