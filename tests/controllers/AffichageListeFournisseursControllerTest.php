<?php

namespace tests\controllers;

use controllers\AffichageListeFournisseursController;
use PHPUnit\Framework\TestCase;
use services\AppelApiService;
use services\FournisseursService;
use services\FactureService;
use yasmf\HttpHelper;
use yasmf\View;

class AffichageListeFournisseursControllerTest extends TestCase
{
    private $fournisseursService;
    private $factureService;
    private $controller;

    protected function setUp(): void
    {
        // Etant donnée une instance de AffichageListeFournisseursController
        $this->fournisseursService = new FournisseursService( new AppelApiService());
        // Et une instance de FactureService
        $this->factureService = new FactureService( new AppelApiService());
        // Et une instance de AffichageListeFournisseursController
        $this->controller = new AffichageListeFournisseursController($this->factureService);

    }
    public function testcheckSession() {
        // Quand la session est vide
        $_SESSION = [];
        $controller = new AffichageListeFournisseursController($this->factureService);
        $view = $controller->checkSession();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);
    }

    public function testAfficherFournisseursSansCle() {

        // Quand la session est vide
        $_SESSION = [];
        $view = $this->controller->afficherFournisseurs();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);
    }
    public function testAfficherFournisseursAvecCle() {
        // Quand la session est définie
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $view = $this->controller->afficherFournisseurs();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);
    }
    public function testRecherche() {

        // Quand la session est vide
        $_SESSION = [];
        $view = $this->controller->recherche();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);
    }
    public function testRechercheWithParamsSansCle() {
        // Quand la session est vide
        $_SESSION = [];
        // Quand les paramètres sont définis
        $_GET['nom_soc'] = 'nom';
        $_GET['lib_soc'] = 'lib';
        $_GET['paye'] = '1';
        $_GET['montant'] = '100';
        $_GET['num_facture'] = 'num';
        $_GET['ref_fournisseur'] = 'ref';;
        $view = $this->controller->recherche();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);
    }
    public function testRechercheWithParamsAvecCle() {
        // Quand la session est définie
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        // Quand les paramètres sont définis
        $_GET['nom_soc'] = 'nom';
        $_GET['lib_soc'] = 'lib';
        $_GET['paye'] = '1';
        $_GET['montant'] = '100';
        $_GET['num_facture'] = 'num';
        $_GET['ref_fournisseur'] = 'ref';
        $view = $this->controller->recherche();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);

    }

    public function testRemiseAZeroSansCle() {

        // Quand la session est vide
        $_SESSION = [];
        $view = $this->controller->remiseAZero();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);
    }
    public function testRemiseAZeroAvecCle() {
        // Quand la session est définie
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $view = $this->controller->remiseAZero();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);
        // Et les paramètres sont passés à la vue sont vides
        $this->assertEquals('', $view->getVar('nom'));
        $this->assertEquals('', $view->getVar('lib'));
        $this->assertEquals('', $view->getVar('paye'));
        $this->assertEquals('', $view->getVar('montant'));
        $this->assertEquals('', $view->getVar('num_facture'));
        $this->assertEquals('', $view->getVar('ref_fournisseur'));
    }

    public function testAfficherVueDebutSansCle() {
        // Quand la session est vide
        $_SESSION = [];
        $view = $this->controller->afficherVueDebut();
        // Alors la vue de connexion est retournée
        $this->assertInstanceOf(View::class, $view);
    }
    public function testAfficherVueDebutAvecCle() {
        // Quand la session est définie
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $view = $this->controller->afficherVueDebut();
        // Alors la vue de connexion est retournée
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

}