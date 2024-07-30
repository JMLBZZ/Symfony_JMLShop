<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);//va chercher le formulaire "RegisterUserType" et crée le!

        $form->handleRequest($request);//écoute la request pour aller plus loin

        if ($form->isSubmitted() && $form->isValid()){//si le formulaire est soumis et valide, alors...
            $entityManager->persist($user);// ... tu persistes les données ($user = objets en liens avec l'entité) et,
            $entityManager->flush();//... tu enregistres les données dans la DB

            // ... tu affiches l'alert
            $this->addFlash(
                'success',//type de notif (couleur success bootstrap)
                'Votre compte est créé! Vous pouvez vous connecter.'//message de la notif
            );

        // ################## Mail de confirmation d'inscription ############### //
        // ############################## MAILJET ############################## //
            $mail = new Mail();
            $vars = [
                "firstname" => $user->getFirstname(),
                "lastname" => $user->getLastname(),
            ];
            $mail->send($user->getEmail(), $user->getLastname()." ".$user->getFirstname(), "Bienvenue sur CAP NATION", "welcome.html", $vars);
        // ############################ fin de mail ############################ //

            // ... tu rediriges sur la page de login
            return $this->redirectToRoute("app_login");
        }

        return $this->render('register/index.html.twig', [
            "registerForm" => $form->createView()
        ]);
    }
}
