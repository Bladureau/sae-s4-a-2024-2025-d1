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

namespace controllers;

use services\ConnexionService;
use services\FournisseursService;
use services\FactureService;
use yasmf\View;

/**
 * Contrôleur principal pour gérer les opérations liées à la page d'accueil et les redirections.
 */
class HomeController
{
    private ConnexionService $connexionService;
    private $fournisseursService;
    private $FactureService;

    /**
     * Constructeur pour initialiser les services nécessaires.
     *
     * @param ConnexionService $connexionService Service pour gérer les connexions API.
     * @param FournisseursService $fournisseursService Service pour gérer les données des fournisseurs.
     * @param FactureService $FactureService Service pour gérer les données des factures.
     */
    public function __construct(ConnexionService $connexionService, FournisseursService $fournisseursService, FactureService $FactureService)
    {
        $this->fournisseursService = $fournisseursService;
        $this->FactureService = $FactureService;
        $this->connexionService = $connexionService;
    }

    /**
     * Affiche la vue de connexion par défaut.
     *
     * @return View Vue de la page de connexion.
     */
    public function index(): View {
        return new View("views/connexion");
    }

    /**
     * Redirige vers la page d'accueil après vérification de la session.
     *
     * @return View Vue de la page d'accueil.
     * @throws \Exception Si la clé API n'est pas définie dans la session.
     */
    public function redirectionAcceuil(): View {
        // Démarre la session
        session_start();

        // Vérifie si la clé API est définie dans la session
        if (!isset($_SESSION['cleApi'])) {
            $_SESSION['cleApi'] = null;
        }

        $apiKey = $_SESSION['cleApi'];

        // Si la clé API est absente, une exception est levée
        if ($apiKey === null) {
            throw new \Exception("La clé API n'est pas définie dans la session.");
        }

        // Récupère la liste des fournisseurs via le service
        $fournisseurs = $this->fournisseursService->getFournisseurs();

        // Prépare la vue de la page d'accueil
        $view = new View("views/acceuil");

        // Passe les données des fournisseurs à la vue
        $view->setVar("fournisseurs", $fournisseurs);

        return $view;
    }
}
?>