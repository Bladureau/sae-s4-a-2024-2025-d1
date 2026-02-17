<?php

namespace controllers;

use PHPUnit\Framework\TestCase;
use services\AppelApiService;
use services\FactureService;
use services\FournisseursService;
use yasmf\View;

class AffichagePalmaresFournisseursControllerTest extends TestCase
{
    private AffichagePalmaresFournisseursController $controller;
    private FournisseursService $fournisseursService;

    protected function setUp(): void
    {

        // Démarre la session pour les tests
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Simule une session authentifiée
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';

        // Etant donnée une instance de FournisseurService
        $this->fournisseursService = new FournisseursService(new AppelApiService());

        // Et une instance de AffichagePalmaresFournisseursController
        $this->controller = new AffichagePalmaresFournisseursController($this->fournisseursService);
    }

    protected function tearDown(): void
    {
        // Nettoye la session après chaque test
        session_unset();
        session_destroy();

        parent::tearDown();
    }

    /**
     * @test Test de la méthode checkSession
     * @description Vérifie que la méthode checkSession retourne une instance de View
     * si la session n'est pas authentifiée
     */
    public function testCheckSession(): void
    {
        // Etant donnée une session non authentifiée (sans cleApi)
        $_SESSION['cleApi'] = null;

        // Quand on appelle la méthode checkSession
        $view = $this->controller->checkSession();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }

    /**
     * @test Test de la méthode afficherVueDebut
     * @description Vérifie que la méthode afficherVueDebut retourne une instance de View
     * avec la configuration initiale pour l'affichage du palmarès des fournisseurs
     */
    public function testAfficherVueDebut(): void
    {
        // Etant donnée une session authentifiée
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';

        // Quand on appelle la méthode afficherVueDebut
        $view = $this->controller->afficherVueDebut();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }


    /**
     * @test Test de la méthode recherche
     * @description Vérifie que la méthode recherche retourne une instance de View
     * avec les données du palmarès des fournisseurs
     */
    public function testRecherche(): void
    {
        session_start();
        // Etant donnée une session authentifiée
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';

        // Etant donnée des paramètres GET pour la recherche
        $_GET['date_debut'] = '2025-01-01';
        $_GET['date_fin'] = '2025-12-31';
        $_GET['topSelector'] = '10';

        // Quand on appelle la méthode recherche
        $view = $this->controller->recherche();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);

        // Vérifier que les paramètres de recherche ont été correctement utilisés
        $this->assertEquals($_GET['date_debut'], $view->getVar('date_debut'));
        $this->assertEquals($_GET['date_fin'], $view->getVar('date_fin'));
        $this->assertEquals($_GET['topSelector'], $view->getVar('top'));
    }
    /**
     * @test Test de la méthode recherche sans clé API
     * @description Vérifie que la méthode recherche retourne une instance de View
     */
    public function testRechercheSansCleApi(): void
    {
        // Etant donnée une session non authentifiée (sans cleApi)
        $_SESSION['cleApi'] = null;

        // Quand on appelle la méthode recherche
        $view = $this->controller->recherche();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }

    /**
     * @test Test de la méthode remiseAZero
     * @description Vérifie que la méthode remiseAZero retourne une instance de View
     * avec les données du palmarès des fournisseurs remises à zéro
     */
    public function testRemiseAZero(): void
    {
        session_start();
        // Etant donnée une session authentifiée
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';

        // Quand on appelle la méthode remiseAZero
        $view = $this->controller->remiseAZero();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);


        // Vérifie que les paramètres ont été réinitialisés à leurs valeurs par défaut
        $this->assertEquals("", $view->getVar('date_debut'));
        $this->assertEquals("", $view->getVar('date_fin'));
        $this->assertEquals("0", $view->getVar('top'));

    }

    /**
     * @test Test de la méthode redirectionAcceuil
     * @description Vérifie que la méthode redirectionAcceuil retourne une instance de View
     * avec le template approprié
     */
    public function testRedirectionAcceuil(): void
    {
        // Quand on appelle la méthode redirectionAcceuil
        $view = $this->controller->redirectionAcceuil();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);

    }

    /**
     * @test Test de la méthode redirectionNoteFrais
     * @description Vérifie que la méthode redirectionNoteFrais retourne une instance de View
     * avec le template approprié
     */
    public function testRedirectionNoteFrais(): void
    {
        // Quand on appelle la méthode redirectionNoteFrais
        $view = $this->controller->redirectionNoteFrais();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }

    /**
     * @test Test de la méthode redirectionFournisseurs
     * @description Vérifie que la méthode redirectionFournisseurs retourne une instance de View
     * avec le template approprié
     */
    public function testRedirectionFournisseurs(): void
    {
        // Quand on appelle la méthode redirectionFournisseurs
        $view = $this->controller->redirectionFournisseurs();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }


}
