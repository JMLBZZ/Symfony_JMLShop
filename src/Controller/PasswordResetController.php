<?php

namespace App\Controller;

use App\Class\Mail;
use App\Repository\UserRepository;
use App\Form\PasswordResetFormType;
use App\Form\PasswordUpdateFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordResetController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/resetpwd', name: 'app_resetpwd')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(PasswordResetFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //si email dans bdd, alors...
            $email = $form->get("email")->getData();//récupère uniquement l'email
            $user = $userRepository->findOneByEmail($email);

            $this->addFlash(
                'success', // type de notif (couleur success bootstrap)
                "Si votre compte existe, vous recevrez un mail de réinitialisation d'une durée de 30 minutes.<br/>
                Sinon, veuillez refaire la demande de réinitialisation où de créer un compte." // message de la notif
            );

            if($user){
            // ############################ TOKEN PERSO ############################ //
                //créer un token et stocker dans la bdd
                $token = bin2hex(random_bytes(20));//transfo le nombre d'octets qu'on souhaites générer (ici 20 octets) en vc aléatoire lisible,
                    //dd($token);
                $user->setToken($token);

                //temps d'expiration
                $date = new DateTime();
                $date->modify("+30 minutes");//ajoute 30min à la date actuelle
                $user->setTokenExpireAt($date);//stock l'objet date

                $this->em->flush();//stock dans la bdd
                    //dd($user);
                

            // ############################## MAILJET ############################## //
                $mail = new Mail();
                $vars = [
                    "link" => $this->generateUrl("app_resetpwd_update", ["token" => $token], UrlGeneratorInterface::ABSOLUTE_URL),
                ];
                $mail->send($user->getEmail(), $user->getLastname()." ".$user->getFirstname(), "Modification du mot de passe", "password_reset.html", $vars);
            // ############################ fin de mail ############################ //
            }
        }
        return $this->render('password_reset/index.html.twig',[
            "passwordResetForm" => $form->createView(),
        ]);
    }

    #[Route('/resetpwd/update/{token}', name: 'app_resetpwd_update')]
    public function update(Request $request, UserRepository $userRepository, $token): Response
    {
        //vérification si token n'existe pas
        if (!$token) {
            $this->addFlash(
                'success',
                "Votre demande de réinitialisation de mot de passe a expiré.<br/>Veuillez refaire une demande."
            );
            return $this->redirectToRoute('app_resetpwd');
        }

        $user = $userRepository->findOneByToken($token);
        $now = new DateTime();

        //vérification si user n'existe pas OU token de l'utilisateur est expiré (comparaison date actuelle avec date d'expiration)
        if (!$user || $now > $user->getTokenExpireAt()) {
            $this->addFlash(
                'success',
                "Votre demande de réinitialisation de mot de passe a expiré.<br/>
                Veuillez refaire une demande."
            );
            return $this->redirectToRoute('app_resetpwd');
        }

        $form = $this->createForm(PasswordUpdateFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setToken(null);
            $user->setTokenExpireAt(null);
            
            $this->em->flush();
            $this->addFlash(
                'success',
                "Votre mot de passe est mis à jour. <br/>
                Vous pouvez vous connecter avec votre nouveau mot de pass"
            );
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_reset/update.html.twig',[
            "passwordUpdateForm" => $form->createView(),
        ]);
    }
}
