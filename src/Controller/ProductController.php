<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{slug}', name: 'app_product')]
    public function index($slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBySlug($slug);//findOneByNomdelapropriétédel'entité = va afficher la propriété de l'entité product

        if (!$product) {//si catégorie n'existe pas, alors...
            return $this->redirectToRoute("app_home");
        }
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
