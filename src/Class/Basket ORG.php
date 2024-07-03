<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

class Basket{
    // Session du panier :
    public function __construct(private RequestStack $requestStack)
    {
        
    }



    public function add($product){
        //appel de la session
            //$session = $this->requestStack->getSession();
        //dd($session);
        //rapeller le panier (produit) précédemment ajouté:
        $basket = $this->requestStack->getSession()->get("basket");

        // ajout +1 du produit dans le panier
        if ($basket[$product->getId()]) {//si le produit est dans le panier
            $basket[$product->getId()] = [ //créer le tableau id de produit avec objet et la quantité
                "object" => $product,
                "qty" => $basket[$product->getId()]["qty"] + 1 //...ajoute +1 à ce même produit identique
            ];
        } else {//sinon...
            $basket[$product->getId()] = [
                "object" => $product,
                "qty" => 1 //...ajoute +1 au nouveau produit
            ];
        }

        //créer la session Basket
        $this->requestStack
            ->getSession()
            ->set("basket", $basket);//nom de la session, et valeur de la session
        
    }

    public function getBasket(){
        return $this->requestStack->getSession()->get("basket");
    }
}