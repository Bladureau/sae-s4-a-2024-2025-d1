<?php

namespace tests\controllers;

use controllers\AffichageListeNoteFraisController;
use PHPUnit\Framework\TestCase;
use services\AppelApiService;
use services\NotesDeFraisService;
use yasmf\ComponentFactory;
use yasmf\HttpHelper;
use yasmf\View;

class AffichageListeNoteFraisControllerTest extends TestCase
{
    private AffichageListeNoteFraisController $controller;
    private NotesDeFraisService $notesDeFraisService;

    protected function setUp(): void
    {
        parent::setUp();

        // Démarre la session pour les tests
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Simule une session authentifiée
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';

        // Etant donnée une instance de NotesDeFraisService
        $this->notesDeFraisService = new NotesDeFraisService(new AppelApiService());

        // Et une instance de AffichageListeNoteFraisController
        $this->controller = new AffichageListeNoteFraisController($this->notesDeFraisService);
    }

    protected function tearDown(): void
    {
        // Nettoye la session après chaque test
        session_unset();
        session_destroy();

        parent::tearDown();
    }

    /**
     * @test Test de la méthode recherche
     * @description Vérifie que la méthode recherche retourne une instance de View avec les critères de recherche
     */
    public function testRecherche(): void
    {
        // Prépare les paramètres de recherche
        $_GET['ref_note'] = 'REF123';
        $_GET['crea_note'] = '1';
        $_GET['valid_note'] = '1';
        $_GET['date_creation'] = '2025-01-01';
        $_GET['date_fin'] = '2025-12-31';
        $_GET['paye'] = '1';
        $_GET['montant'] = '100';
        $_GET['type_frais'] = 'transport';

        // Quand on appelle la méthode recherche
        $view = $this->controller->recherche();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);


        // Et on s'attend à ce que les critères de recherche soient passés à la vue
        $this->assertEquals('REF123', $view->getVar('ref_note'));
        $this->assertEquals('1', $view->getVar('crea_note'));
        $this->assertEquals('1', $view->getVar('valid_note'));
        $this->assertEquals('2025-01-01', $view->getVar('date_creation'));
        $this->assertEquals('2025-12-31', $view->getVar('date_fin'));
        $this->assertEquals('1', $view->getVar('paye'));
        $this->assertEquals('100', $view->getVar('montant'));
        $this->assertEquals('transport', $view->getVar('type_frais'));
    }

    /**
     * @test Test de la méthode remiseAZero
     * @description Vérifie que la méthode remiseAZero retourne une instance de View avec les critères réinitialisés
     */
    public function testRemiseAZero(): void
    {
        // Quand on appelle la méthode remiseAZero
        $view = $this->controller->remiseAZero();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);


        // Et on s'attend à ce que les critères de recherche soient réinitialisés
        $this->assertEquals('', $view->getVar('ref_note'));
        $this->assertEquals('', $view->getVar('crea_note'));
        $this->assertEquals('', $view->getVar('valide_note'));
        $this->assertEquals('', $view->getVar('date_creation'));
        $this->assertEquals('', $view->getVar('date_fin'));
        $this->assertEquals('', $view->getVar('paye'));
        $this->assertEquals('', $view->getVar('montant'));
        $this->assertEquals('', $view->getVar('type_frais'));
    }

    /**
     * @test Test des méthodes d'affichage de vues
     * @description Vérifie que les méthodes retournent les bonnes vues
     */
    public function testAffichageListeNoteFraisVide(): void
    {
        // Quand on appelle la méthode affichageListeNoteFrais
        $view = $this->controller->affichageListeNoteFraisVide();
        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }
    /**
     * @test Test des méthodes d'affichage de vues
     * @description Vérifie que les méthodes retournent les bonnes vues
     */
    public function testAffichageSectorielNotesFrais(): void
    {
        // Quand on appelle la méthode affichageSectorielNotesFrais
        $view = $this->controller->affichageSectorielNotesFrais();
        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }
    /**
     * @test Test des méthodes d'affichage de vues
     * @description Vérifie que les méthodes retournent les bonnes vues
     */
    public function testAffichageEvolutionNotesFrais(): void
    {
        // Quand on appelle la méthode affichageEvolutionNotesFrais
        $view = $this->controller->affichageEvolutionNotesFrais();
        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);
    }

    /**
     * @test Test des méthodes de redirection
     * @description Vérifie que les méthodes retournent les bonnes vues de redirection
     */
    public function testRedirectionMethods(): void
    {
        // Test redirectionAcceuil
        $view = $this->controller->redirectionAcceuil();
        $this->assertInstanceOf(View::class, $view);

        // Test redirectionNoteFrais
        $view = $this->controller->redirectionNoteFrais();
        $this->assertInstanceOf(View::class, $view);

        // Test redirectionFournisseurs
        $view = $this->controller->redirectionFournisseurs();
        $this->assertInstanceOf(View::class, $view);
    }

    public function testAffichageListeNoteFrais()
    {
        // Quand on appelle la méthode affichageListeNoteFrais
        $view = $this->controller->affichageListeNoteFrais();

        // Alors on s'attend à obtenir une instance de View
        $this->assertInstanceOf(View::class, $view);

        // Et on s'attend à ce que la vue contienne la liste des notes de frais
        $this->assertIsArray($view->getVar('listeNotes'));
    }
    /**
     * @test Test de la méthode checkSession
     * @description Vérifie que la méthode checkSession retourne la vue de connexion
     * si l'utilisateur n'est pas authentifié, et null sinon
     */
    public function testCheckSession(): void
    {
        // Cas 1: Session non authentifiée (pas de clé API)
        unset($_SESSION['cleApi']);

        // Quand on appelle checkSession
        $view = $this->controller->checkSession();

        // Alors on s'attend à obtenir une instance de View pour la page de connexion
        $this->assertInstanceOf(View::class, $view);

        // Cas 2: Session avec clé API vide
        $_SESSION['cleApi'] = '';

        // Quand on appelle checkSession
        $view = $this->controller->checkSession();

        // Alors on s'attend à obtenir une instance de View pour la page de connexion
        $this->assertInstanceOf(View::class, $view);

        // Cas 3: Session authentifiée
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';

        // Quand on appelle checkSession
        $view = $this->controller->checkSession();

        // Alors on s'attend à obtenir null (l'utilisateur est authentifié)
        $this->assertNull($view);
    }

}
