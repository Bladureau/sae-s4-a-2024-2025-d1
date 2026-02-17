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

use controllers\ConnexionController;
use controllers\HomeController;
use controllers\AffichageListeNoteFraisController;
use controllers\AffichageListeFournisseursController;
use controllers\AffichagePalmaresFournisseursController;
use services\ConnexionService;
use services\FactureService;
use services\NotesDeFraisService;
use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;
use services\FournisseursService;
use services\AppelApiService;


/**
 *  The controller factory
 */
class DefaultComponentFactory implements ComponentFactory
{
    private ?ConnexionService $connexionService = null;
    private ?NotesDeFraisService $notesDeFraisService = null;
    private ?FournisseursService $fournisseursService = null;
    private ?FactureService $factureService = null;

    /**
     * @param string $controller_name the name of the controller to instanciate
     * @return mixed the controller
     * @throws NoControllerAvailableForNameException when controller is not found
     */
    public function buildControllerByName(string $controller_name): mixed {
        return match ($controller_name) {
            "Home" => $this->buildHomeController(),
            "Connexion" => $this->buildConnexionController(),
            "AffichageListeNoteFrais" => $this->buildAffichageListeNoteFraisController(),
            "AffichageListeFournisseurs" => $this->buildAffichageListeFournisseursController(),
            "AffichagePalmaresFournisseurs" => $this->buildAffichagePalmaresFournisseursController(),
            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    /**
     * @param string $service_name the name of the service
     * @return mixed the created service
     * @throws NoServiceAvailableForNameException when service is not found
     */
    public function buildServiceByName(string $service_name): mixed
    {
        return match($service_name) {
            "Connexion" => $this->buildConnexionService(),
            "NotesDeFrais" => $this->buildNotesDeFraisService(),
            "Fournisseurs" => $this->buildFournisseursService(),
            "Facture" => $this->buildFactureService(),
            default => throw new NoServiceAvailableForNameException($service_name)
        };
    }

    /**
     * @return ConnexionService
     */
    private function buildConnexionService(): ConnexionService
    {
        if ($this->connexionService == null) {

            $this->connexionService = new ConnexionService( new AppelApiService());
        }
        return $this->connexionService;
    }

    /**
     * @return NotesDeFraisService
     */
    private function buildNotesDeFraisService(): NotesDeFraisService
    {
        if ($this->notesDeFraisService == null) {

            $this->notesDeFraisService = new NotesDeFraisService( new AppelApiService());
        }
        return $this->notesDeFraisService;

    }

    /**
     * @return FournisseursService
     */
    private function buildFournisseursService(): FournisseursService
    {
        if ($this->fournisseursService == null) {
            $this->fournisseursService = new FournisseursService( new AppelApiService());
        }
        return $this->fournisseursService;
    }

    /**
     * @return FactureService
     */
    private function buildFactureService(): FactureService
    {
        if ($this->factureService == null) {
            $this->factureService = new FactureService( new AppelApiService());
        }
        return $this->factureService;
    }

    /**
     * @return HomeController
     */
    private function buildHomeController(): HomeController
    {
        return new HomeController($this->buildServiceByName("Connexion"), $this->buildServiceByName("Fournisseurs"), $this->buildServiceByName("Facture"));
    }

    /**
     * @return AffichageListeNoteFraisController
     */
    private function buildAffichageListeNoteFraisController(): AffichageListeNoteFraisController
    {
        return new AffichageListeNoteFraisController($this->buildServiceByName("NotesDeFrais"));
    }

    /**
     * @return AffichageListeFournisseursController
     */
    private function buildAffichageListeFournisseursController(): AffichageListeFournisseursController
    {
        return new AffichageListeFournisseursController($this->buildServiceByName("Facture"));
    }

    /**
     * @return AffichageListeFournisseursController
     */
    private function buildAffichagePalmaresFournisseursController(): AffichagePalmaresFournisseursController
    {
        return new AffichagePalmaresFournisseursController($this->buildServiceByName("Fournisseurs"));
    }

    /**
     * @return ConnexionController
     */
    private function buildConnexionController(): ConnexionController
    {
        return new ConnexionController($this->buildServiceByName("Connexion"), $this->buildServiceByName("Fournisseurs"));
    }
}