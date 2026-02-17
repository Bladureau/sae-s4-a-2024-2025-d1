<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace application;

use controllers\AffichagePalmaresFournisseursController;
use controllers\ConnexionController;
use controllers\HomeController;
use controllers\AffichageListeNoteFraisController;
use controllers\AffichageListeFournisseursController;
use PHPUnit\Framework\TestCase;
use services\AppelApiService;
use services\FactureService;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;
use services\ConnexionService;
use services\NotesDeFraisService;
use services\FournisseursService;

class DefaultComponentFactoryTest extends TestCase
{
    // Component factory to test
    private DefaultComponentFactory $componentFactory;

    protected function setUp(): void
    {
        // Etant donnée un component factory par défaut
        $this->componentFactory = new DefaultComponentFactory();
    }
    /**
     * Test the creation of HomeController.
     * @test
     * @description "Teste la création d'un contrôleur HomeController."
     */
    public function testBuildControllerByName_Home()
    {
        // Quand on demande la création d'un contrôleur "Home"
        $controller = $this->componentFactory->buildControllerByName("Home");
        // Alors le contrôleur créé est une instance de HomeController
        self::assertInstanceOf(HomeController::class, $controller);
    }

    /**
     * @test
     * Test the creation of ConnexionController.
     * @description "Teste la création d'un contrôleur Connexion."
     */
    public function testBuildControllerByName_Connexion()
    {
        // Quand on demande la création d'un contrôleur "Connexion"
        $controller = $this->componentFactory->buildControllerByName("Connexion");
        // Alors le contrôleur créé est une instance de ConnexionController
        self::assertInstanceOf(ConnexionController::class, $controller);
    }

    /**
     * @test
     * Test the creation of AffichageListeNoteFraisController.
     * @description "Teste la création d'un contrôleur AffichageListeNoteFrais."
     */
    public function testBuildControllerByName_AffichageListeNoteFrais()
    {
        // Quand on demande la création d'un contrôleur "AffichageListeNoteFrais"
        $controller = $this->componentFactory->buildControllerByName("AffichageListeNoteFrais");
        // Alors le contrôleur créé est une instance de AffichageListeNoteFraisController
        self::assertInstanceOf(AffichageListeNoteFraisController::class, $controller);
    }

    /**
     * @test
     * Test the creation of AffichageListeFournisseursController.
     * @description "Teste la création d'un contrôleur AffichageListeFournisseurs."
     */
    public function testBuildControllerByName_AffichageListeFournisseurs()
    {
        // Quand on demande la création d'un contrôleur "AffichageListeFournisseurs"
        $controller = $this->componentFactory->buildControllerByName("AffichageListeFournisseurs");
        // Alors le contrôleur créé est une instance de AffichageListeFournisseursController
        self::assertInstanceOf(AffichageListeFournisseursController::class, $controller);
    }
    /**
     * @test
     * Test the cration of buildAffichagePalmaresFournisseursController
     * @description "Teste la création d'un contrôleur AffichagePalmaresFournisseurs."
     */
    public function testBuildControllerByName_AffichagePalmaresFournisseurs(){
        // Quand on demande la création d'un contrôleur "AffichagePalmaresFournisseurs"
        $controller = $this->componentFactory->buildControllerByName("AffichagePalmaresFournisseurs");
        // Alors le contrôleur créé est une instance de AffichagePalmaresFournisseursController
        self::assertInstanceOf(AffichagePalmaresFournisseursController::class, $controller);
    }



    /**
     * @test
     * Test exception when requesting a non-existent controller.
     * @description "Teste l'exception lorsqu'on demande un contrôleur inexistant."
     */
    public function testBuildControllerByName_Other()
    {
        // Etant donnée un component factory par défaut
        $componentFactory = new DefaultComponentFactory();
        // Quand on demande la création d'un contrôleur "NoController"
        // Alors une exception est levée
        $this->expectException(NoControllerAvailableForNameException::class);
        $componentFactory->buildControllerByName("NoController");
    }

    /**
     * @test
     * Test the creation of ConnexionService.
     * @description "Teste la création d'un service Connexion."
     */
    public function testBuildServiceByName_Connexion()
    {
        // Quand on demande la création d'un service "Connexion"
        $service = $this->componentFactory->buildServiceByName("Connexion");
        // Alors le service créé est une instance de ConnexionService
        self::assertInstanceOf(ConnexionService::class, $service);
    }

    /**
     * @test
     * Test the creation of NotesDeFraisService.
     * @description "Teste la création d'un service NotesDeFrais."
     */
    public function testBuildServiceByName_AffichageListeNoteFrais()
    {
        // Quand on demande la création d'un service "AffichageListeNoteFrais"
        $service = $this->componentFactory->buildServiceByName("NotesDeFrais");
        // Alors le service créé est une instance de NotesDeFraisService
        self::assertInstanceOf(NotesDeFraisService::class, $service);
    }

    /**
     * @test
     * Test the creation of FournisseursService.
     * @description "Teste la création d'un service Fournisseurs."
     */
    public function testBuildServiceByName_AffichageListeFournisseurs()
    {
        // Quand on demande la création d'un service "AffichageListeFournisseurs"
        $service = $this->componentFactory->buildServiceByName("Fournisseurs");
        // Alors le service créé est une instance de FournisseursService
        self::assertInstanceOf(FournisseursService::class, $service);
    }
    /**
     * @test
     * Test the creation of FactureService.
     * @description "Teste la création d'un service Facture."
     */
    public function testBuildServiceByName_AffichagePalmaresFournisseurs(){
        // Quand on demande la création d'un service "AffichagePalmaresFournisseurs"
        $service = $this->componentFactory->buildServiceByName("Facture");
        // Alors le service créé est une instance de FactureService
        self::assertInstanceOf(FactureService::class, $service);
    }



    /**
     * @test
     * Test exception when requesting a non-existent service.
     * @description "Teste l'exception lorsqu'on demande un service inexistant."
     */
    public function testBuildServiceByName_Other()
    {
        // Etant donnée un component factory par défaut
        $componentFactory = new DefaultComponentFactory();
        // Quand on demande la création d'un service "NoService"
        // Alors une exception est levée
        $this->expectException(NoServiceAvailableForNameException::class);
        $componentFactory->buildServiceByName("NoService");
    }
}
