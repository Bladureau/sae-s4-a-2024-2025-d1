<?php

namespace controllers;

use PHPUnit\Framework\TestCase;
use services\AppelApiService;
use services\FournisseursService;
use yasmf\HttpHelper;
use yasmf\View;
use services\ConnexionService;

class ConnexionControllerTest extends TestCase
{

    public function setUp(): void
    {
        // Etant donné un ConnexionController
        $this->connexionController = new ConnexionController(new ConnexionService(new AppelApiService()), new FournisseursService(new AppelApiService()));
    }

    /**
     * @test Test de la méthode getUser
     * Test de la méthode getUser
     * @description Quand on appelle la méthode getUser, alors on s'attend à obtenir une instance de View
     */
    public function testGetUser(): void
    {
        // Préparation des données de la requête
        $_POST['username'] = 'grouped1';
        $_POST['password'] = 'HXdnPDOZR1l8';
        $_POST['url'] = 'http://dolibarr.iut-rodez.fr/G2024-41-SAE';

        // Démarre la session si ce n'est pas déjà fait
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Quand on appelle la méthode getUser
        $view = $this->connexionController->getUser();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);


        // Nettoye la session après le test
        session_unset();
        session_destroy();
    }
    /**
     * @test Test de la méthode getUser sans clé API
     * Test de la méthode getUser sans clé API
     * @description Quand on appelle la méthode getUser sans clé API,
     * alors on s'attend à obtenir une instance de View connexion
     */

    public function testGetUserWithoutApiKey(): void
    {
        // Préparation des données de la requête
        $_POST['username'] = 'grouped11';
        $_POST['password'] = 'HXdnPDOZR1l8';
        $_POST['url'] = 'http://dolibarr.iut-rodez.fr/G2024-41-SAE';

        // Quand on appelle la méthode getUser
        $view = $this->connexionController->getUser();

        // Alors on s'attend à obtenir une instance de View
        // Et plus précisément une instance de View connexion
        $this->assertInstanceOf(View::class, $view);

        // Nettoye la session après le test
        session_unset();
        session_destroy();
    }




    /**
     * @test Test de la méthode deconnexion
     * Test de la méthode deconnexion
     * @description Quand on appelle la méthode deconnexion, alors on s'attend à obtenir une instance de View
     */
    public function testDeconnexion(): void
    {
        // Quand on appelle la méthode deconnexion
        $view = $this->connexionController->deconnexion();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }
}
