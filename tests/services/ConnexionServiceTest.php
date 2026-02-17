<?php

namespace services;

use PHPUnit\Framework\TestCase;
use services\ConnexionService;

class ConnexionServiceTest extends TestCase
{
    private ConnexionService $connexionService;
    private string $validLogin = "hdelacroix";
    private string $validPassword = "ecI563pA6lN2";

    private string $apiUrl = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";



    public function setUp(): void
    {
        // Etant un service de connexion a l'API
        $this->connexionService = new ConnexionService( new AppelApiService());
    }
    /**
     * @test
     * Test de la connexion à l'API
     * @description Test de la connexion à l'API avec des identifiants valides
     */
    public function testConnexionAPINominal()
    {
        // Qauand je demande la connexion avec un login et un mot de passe valides
        $result = $this->connexionService->connexionAPI($this->validLogin, $this->validPassword, $this->apiUrl);
        // Alors l'api doit me retourner une reponse non vide / la clé api de l'utilisateur
        $this->assertNotEmpty($result, "La reponse de l'api doit etre non vide");

        // Et la clé api doit etre egale a la clé api de l'utilisateur
        $this->assertEquals($result, "3ecI563pA6lN2MRIIhNkn8Ca3c01ByGa", "La cle api doit etre 
                                              egale a la cle api de l'utilisateur");
    }

    /**
     * @test
     * Test de la connexion à l'API avec un login vide
     * @description Test de la connexion à l'API avec un login vide
     */
    public function testConnexionAPILimiteLoginVide()
    {
        $login = "";

        // Quand je demande la connexion avec un login vide
        $result = $this->connexionService->connexionAPI($login, $this->validPassword, $this->apiUrl);

        // Alors l'api doit me retourner une reponse vide
        $this->assertEmpty($result, "Token should be empty for empty login");
    }
    /**
     * @test
     * Test de la connexion à l'API avec un mot de passe vide
     * @description Test de la connexion à l'API avec un mot de passe vide
     */
    public function testConnexionAPILimitePasswordVide()
    {
        $pwd = "";

        // Quand je demande la connexion avec un mot de passe vide
        $result = $this->connexionService->connexionAPI($this->validLogin, $pwd, $this->apiUrl);

        // Alors l'api doit me retourner une reponse vide
        $this->assertEmpty($result, "Token should be empty for empty password");
    }

    /**
     * @test
     * Test de la connexion à l'API avec un login et un mot de passe vide
     * @description Test de la connexion à l'API avec un login et un mot de passe vide
     */
    public function testConnexionAPILimiteLoginPasswordVide()
    {
        $login = "";
        $pwd = "";

        // Quand je demande la connexion avec un login et un mot de passe vide
        $result = $this->connexionService->connexionAPI($login, $pwd, $this->apiUrl);

        // Alors l'api doit me retourner une reponse vide
        $this->assertEmpty($result, "Token should be empty for empty login and password");
    }
}

