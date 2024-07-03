<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {

        $client = static::createClient();// émule (simule) un navigateur
        $client->request("GET", "/register");//pointe vers une url de méthode get sur la route de la page d'inscription

        // attention : pour les formulaire il faut récupérer l'attribut "name" qui à le vrai nom du champs (faire un clic droit > inspecter)

        $client->submitForm("S'inscrire",[//2 paramètre : le nom du bouton (ici s'inscrire) et  un tableau où il y a les champs du formulaire
            "register_user[email]" => "test@testfonctionnel.fr",
            "register_user[plainPassword][first]" => "testfonctionnel",
            "register_user[plainPassword][second]" => "testfonctionnel",
            "register_user[firstname]" => "ptest",
            "register_user[lastname]" => "Ntest"
        ]);

        $this->assertResponseRedirects("/login");//test la redirection vers login
        $client->followRedirect();//suis la redirection (car il y a une redirection vers la page de login)

        $this -> assertSelectorExists('div:contains("Votre compte est créé! Vous pouvez vous connecter.")');//voir si dans la page on a le message d'alerte comportant le message "Votre compte est créé! Vous pouvez vous connecter." (voir registercontroller dans la section addflash)

    }
}
