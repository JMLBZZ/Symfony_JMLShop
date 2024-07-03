<?php

namespace App\Twig;

use Twig\TwigFilter;
use App\Class\Basket;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
    private $categoryRepository;
    private $basket;

    public function __construct(CategoryRepository $categoryRepository, Basket $basket)
    {
        $this->categoryRepository = $categoryRepository;
        $this->basket = $basket;
    }
    
    // Création d'un filtre
    public function getFilters()
    {
        return [
            new TwigFilter('pricefilter', [$this, 'formatPrice'])//deux paramètres : nom du filtre, et le nom de la fonction
        ];
    }

    public function formatPrice($number)//$number récupère les nombres avant le pipe du filtre (ici PriceTva)
    {
        return number_format($number,"2",",")." €";
    }

////////////////////////////////////
    // Création d'une répétition pour la navbar (variable globales)
    public function getGlobals():array
    {
        return[
            "allCategories"=>$this->categoryRepository->findAll(),
            "totalBasketQty"=>$this->basket->totalQty()
        ];
    }
}

