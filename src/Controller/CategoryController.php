<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);//findOneByNomdelapropriétédel'entité = va afficher la propriété de l'entité category
        
        if (!$category) {//si catégorie n'existe pas, alors...
            return $this->redirectToRoute("app_home");
        }
        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
