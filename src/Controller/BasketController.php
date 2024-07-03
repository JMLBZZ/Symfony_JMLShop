<?php

namespace App\Controller;

use App\Class\Basket;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
    public function index(Basket $basket): Response
    {
        return $this->render('basket/index.html.twig', [
            "basket" => $basket->getBasket(),
            "totalPriceTva" => $basket->getTotalPriceTva()
        ]);
    }
    
    // ################# AJOUTER UN PRODUIT DANS LE PANIER ################# //
    #[Route('/basket/add/{id}', name: 'app_basket_add')]
    public function add($id, Basket $basket, ProductRepository $productRepository, Request $request): Response
    {
        //dd($request->headers->get("referer"));//referer : donne la dernière page visité
        $product = $productRepository->findOneById($id);//récupère le produit

        if ($product) {
            $basket->add($product); // et le passe en paramètre de la fonction add
    
            $this->addFlash(
                'success', // type de notif (couleur success bootstrap)
                'Le produit a été ajouté dans le panier !' // message de la notif
            );
    
            // Récupérer l'URL de la page précédente
            $referer = $request->headers->get('referer');
            return $this->redirect($referer ?: $this->generateUrl('app_basket'));
            
        } else {
            $this->addFlash(
                'danger', // type de notif (couleur danger bootstrap)
                'Produit non trouvé !' // message de la notif
            );
    
            return $this->redirectToRoute("app_basket");
        }
    }
    
    // ################# DIMINUER UN PRODUIT DANS LE PANIER ################# //
    #[Route('/basket/reduce/{id}', name: 'app_basket_reduce')]
    public function reduce($id, Basket $basket): Response
    {
            $basket->reduce($id);
    
            $this->addFlash(
                'success', // type de notif (couleur success bootstrap)
                'Le produit est supprimé du panier !' // message de la notif
            );
    
            return $this->redirectToRoute("app_basket");

    }

    // ############### SUPPRIMER TOUS LES PRODUITS DU PANIER ############### //
    #[Route('/basket/remove', name: 'app_basket_remove')]
    public function remove(Basket $basket): Response
    {
        $basket->remove(); // et le passe en paramètre de la fonction remove
        $this->addFlash(
            'success', // type de notif (couleur success bootstrap)
            'Le panier est vide !' // message de la notif
        );

        return $this->redirectToRoute("app_basket");
    }
}
