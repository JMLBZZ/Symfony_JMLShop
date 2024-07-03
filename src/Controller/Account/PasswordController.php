<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class PasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/updatepwd', name: 'app_account_updatepwd')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $user = $this->getUser();        
        $form = $this->createForm(PasswordUserType::class, $user, [
            "passwordHasher"=>$passwordHasher
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){//si le formulaire est soumis et valide, alors...

            $this->entityManager->flush();//... tu enregistres les données dans la DB

            //... tu affiches la notification alert
            $this->addFlash(
                'success',//type de notif (couleur success bootstrap)
                'Votre mot de passe est mise à jour.'//message de la notif
            );
        }

        return $this->render('account/password/index.html.twig', [
            "updatePdw"=> $form->createView()
        ]);
    }

}