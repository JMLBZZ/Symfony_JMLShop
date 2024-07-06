<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishlistController extends AbstractController
{
    #[Route('/account/favorite', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wishlist/index.html.twig');
    }

    // ######################### AJOUTER UN FAVORIS ######################## //
    #[Route('/account/favorite/add/{id}', name: 'app_account_wishlist_add')]
    public function add(ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, Request $request, $id): Response
    {
        //récupère le produit
        $product = $productRepository->findOneById($id);

        //si produit existe, alors ajoute le produit en favoris
        if ($product) {
            $this->addFlash(
                'success', // type de notif (couleur success bootstrap)
                "L'article' a été ajouté en favoris !" // message de la notif
            );
    
            // Récupérer l'URL de la page précédente
            $referer = $request->headers->get('referer');

            $this->getUser()->addWishlist($product);
            //sauvegarder dans la bdd
            $entityManagerInterface->flush();
        }
        return $this->redirect($referer ?: $this->generateUrl('app_account_wishlist'));


        
    }

    // ######################### SUPPRIMER UN FAVORIS ######################## //
    #[Route('/account/favorite/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove(ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, Request $request, $id): Response
    {
        //récupère le produit
        $product = $productRepository->findOneById($id);

        //si produit existe, alors supprime le produit en favoris
        if ($product) {
            $this->addFlash(
                'success', // type de notif (couleur danger bootstrap)
                'Article supprimé des favoris !' // message de la notif
            ); 
            
            // récupère l'URL de la page précédente
            $referer = $request->headers->get('referer');

            $this->getUser()->removeWishlist($product);
            //sauvegarder dans la bdd
            $entityManagerInterface->flush();
        }else{
            $this->addFlash(
                'danger', // type de notif (couleur danger bootstrap)
                'Article inconnu !' // message de la notif
            );
        }
        return $this->redirect($referer ?: $this->generateUrl('app_account_wishlist'));//redirige vers la dernière page consultée sinon vers la page des favoris

    }
}
