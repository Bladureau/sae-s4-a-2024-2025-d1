<?php

namespace controllers;

use PHPUnit\Framework\TestCase;
use services\AppelApiService;
use services\FactureService;
use services\FournisseursService;
use yasmf\View;

class AffichageFactureControllerTest extends TestCase
{
    private $controller;
    private $factureService;

    protected function setUp(): void
    {
        // Etant donnée un service FournisseursService
        $this->factureService = new FactureService( new AppelApiService());

        // Et un controller AffichageFactureController
        $this->controller = new AffichageFactureController($this->factureService);
    }

    /**
     * Test de la méthode afficherFactures
     */
    public function testAfficherFactures()
    {
        // Quand on appelle la méthode afficherFactures
        $view = $this->controller->affichageFacture();

        // Alors on vérifie que la vue retournée est bien une instance de View
        $this->assertInstanceOf(View::class, $view);
    }


    /**
     * Test de la méthode redirectionAcceuil
     */
    public function testRedirectionAcceuil()
    {
        // Quand on appelle la méthode de redirection vers l'accueil
        $view = $this->controller->redirectionAcceuil();

        // Alors on vérifie que la vue retournée est bien une instance de View
        $this->assertInstanceOf(View::class, $view);

    }

    /**
     * Test de la méthode redirectionNoteFrais
     */
    public function testRedirectionNoteFrais()
    {
        // Quand on appelle la méthode de redirection vers la liste des notes de frais
        $view = $this->controller->redirectionNoteFrais();

        // Alors on vérifie que la vue retournée est bien une instance de View
        $this->assertInstanceOf(View::class, $view);

    }

    /**
     * Test de la méthode redirectionFournisseurs
     */
    public function testRedirectionFournisseurs()
    {
        // Quand on appelle la méthode de redirection vers la liste des fournisseurs
        $view = $this->controller->redirectionFournisseurs();

        // Alors on vérifie que la vue retournée est bien une instance de View
        $this->assertInstanceOf(View::class, $view);
    }
}
