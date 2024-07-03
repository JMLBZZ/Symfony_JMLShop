<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

class Basket{
    // Session du panier :
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function add($product){
        // appel de la session
        $session = $this->requestStack->getSession();

        // rappeler le panier (produit) précédemment ajouté:
        $basket = $session->get("basket", []); // Initialiser le panier à un tableau vide s'il n'existe pas

        // ajout +1 du produit dans le panier
        if (isset($basket[$product->getId()])) { // Vérifie si le produit est dans le panier
            $basket[$product->getId()] = [ // créer le tableau id de produit avec objet et la quantité
                "object" => $product,
                "qty" => $basket[$product->getId()]["qty"] + 1 // ...ajoute +1 à ce même produit identique
            ];
        } else { // sinon...
            $basket[$product->getId()] = [
                "object" => $product,
                "qty" => 1 // ...ajoute +1 au nouveau produit
            ];
        }

        // créer la session Basket
        $session->set("basket", $basket); // nom de la session, et valeur de la session
    }

    public function reduce($id){
        $basket = $this->getBasket();

        if ($basket[$id]["qty"] > 1){//si la quantité est stric sup à 1, alors...
            $basket[$id]["qty"] = $basket[$id]["qty"] - 1;//... alors tu diminue de -1 à la quantité actuelle
        }else{
            unset ($basket[$id]);//unset permet de supprimer une entrée du tableau
        }
        $this->requestStack->getSession()->set("basket", $basket);
    }

    // ############# Calcul du nombre d'article dans le panier ############# //
    public function totalQty(){
        $basket = $this->getBasket();
        $qty = 0;

        if(!isset($basket)){
            return $qty;
        }

        foreach ($basket as $product){
            $qty = $qty + $product["qty"];
        }

        return $qty;
    }

    // ################### Calcul du prix total du panier ################## //
    public function getTotalPriceTva(){
        $basket = $this->getBasket();
        $price = 0;

        if(!isset($basket)){
            return $price;
        }

        foreach ($basket as $product){
            $price = $price + ($product["object"]->getPriceTva() * $product["qty"]);
        }
        
        return $price;
    }

    // ########################### Vide le panier ########################## //
    public function remove(){
        return $this->requestStack->getSession()->remove("basket");
    }
    
    // ######################### Retourne le panier ######################## //
    public function getBasket(){
        return $this->requestStack->getSession()->get("basket");
    }
}
