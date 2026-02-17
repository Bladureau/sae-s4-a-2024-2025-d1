<?php

namespace controllers;

use PHPUnit\Framework\TestCase;
use services\AppelApiService;
use services\FactureService;
use services\FournisseursService;
use yasmf\View;
use yasmf\HttpHelper;
use services\ConnexionService;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private ConnexionService $connexionService;

    public function setUp(): void
    {
        // Etant donnée une instance de ConnexionService
        $this->connexionService = new ConnexionService(new AppelApiService());
        // Et une instance de FournisseursService
        $fournisseursService = new FournisseursService(new AppelApiService());
        // Et une instance de FactureService
        $factureService = new FactureService(new AppelApiService());
        // Et une instance de HomeController
        $this->homeController = new HomeController($this->connexionService, $fournisseursService, $factureService);
    }

    /**
     * @test
     * Test de la méthode index
     * @description Quand la méthode index est appelée, la vue de connexion doit être retournée
     */
    public function testIndex()
    {
        // Quand la méthode index est appelée
        $view = $this->homeController->index();
        // Alors la vue de connexion est retournée
        self::assertInstanceOf(View::class, $view);

    }

    /**
     * @test
     * Test de redirection vers l'accueil (redirectionAcceuil)
     * @description Quand la clé API est présente dans la session
     */
    public function testRedirectionAcceuil()
    {
        // Simule la présence d'une clé API valide dans la session
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';

        // Appel de la méthode redirectionAcceuil
        $view = $this->homeController->redirectionAcceuil();

        // Vérification que la vue retournée est une instance de View
        self::assertInstanceOf(View::class, $view);

    }

    /**
     * @test
     * Test de redirection vers l'accueil (redirectionAcceuil) sans clé API
     * @description Quand la clé API est absente ou invalide dans la session
     * @throws \Exception
     */
    public function testRedirectionAcceuilWithoutApiKey()
    {
        // Etant donné que la clé API est absente dans la session
        $_SESSION['cleApi'] = null;

        // Quand la méthode redirectionAcceuil est appelée, une exception doit être levée.
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("La clé API n'est pas définie dans la session.");

        // Appel de la méthode redirectionAcceuil, cela devrait échouer sans clé API
        $this->homeController->redirectionAcceuil();
    }




}
