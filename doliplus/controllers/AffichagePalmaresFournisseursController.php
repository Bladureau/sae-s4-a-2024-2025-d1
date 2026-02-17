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

use services\FournisseursService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur pour gérer l'affichage et les opérations liées au palmarès des fournisseurs.
 */
class AffichagePalmaresFournisseursController
{
    private $fournisseursService;

    /**
     * Constructeur pour initialiser le service des fournisseurs.
     *
     * @param FournisseursService $fournisseursService Service pour gérer les données des fournisseurs.
     */
    public function __construct(FournisseursService $fournisseursService)
    {
        $this->fournisseursService = $fournisseursService;
    }

    /**
     * Vérifie si la session utilisateur est valide.
     * Si la session est invalide, redirige vers la vue de connexion.
     *
     * @return View|null Vue de connexion si la session est invalide, sinon null.
     */
    public function checkSession(): ?View
    {
        session_start();
        if (!isset($_SESSION['cleApi']) || $_SESSION['cleApi'] == null || $_SESSION['cleApi'] == "") {
            return new View("views/connexion");
        }
        return null;
    }

    /**
     * Recherche des fournisseurs en fonction des critères fournis.
     *
     * @return View Vue contenant les résultats de la recherche.
     */
    public function recherche()
    {
        // Vérifie la session utilisateur
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Récupération des champs du formulaire
        $date_debut = HttpHelper::getParam('date_debut');
        $date_fin = HttpHelper::getParam('date_fin');
        $top_selector = HttpHelper::getParam('topSelector');

        // Récupération des données du palmarès des fournisseurs
        $listePalmaresFournisseurs = $this->fournisseursService->getPalmaresFournisseurs($date_debut, $date_fin);

        // Création de la vue et passage des données
        $view = new View("views/affichagePalmaresFournisseurs");
        $view->setVar("date_debut", $date_debut);
        $view->setVar("date_fin", $date_fin);
        $view->setVar("palmares", $listePalmaresFournisseurs);
        $view->setVar("top", $top_selector);

        return $view;
    }

    /**
     * Réinitialise les filtres de recherche et affiche la liste par défaut des fournisseurs.
     *
     * @return View Vue contenant la liste par défaut des fournisseurs.
     */
    public function remiseAZero()
    {
        // Vérifie la session utilisateur
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Récupération de la liste des fournisseurs
        $listePalmaresFournisseurs = $this->fournisseursService->getFournisseurs();

        // Création de la vue et remise à zéro des champs
        $view = new View("views/affichagePalmaresFournisseurs");
        $view->setVar("listeFacture", $listePalmaresFournisseurs);
        $top_selector = "0";
        $view->setVar("top", $top_selector);
        $view->setVar("date_debut", "");
        $view->setVar("date_fin", "");

        return $view;
    }

    /**
     * Affiche la vue initiale du palmarès des fournisseurs.
     *
     * @return View Vue contenant la vue initiale.
     */
    public function afficherVueDebut()
    {
        // Vérifie la session utilisateur
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Création de la vue avec des champs vides
        $view = new View("views/affichagePalmaresFournisseurs");
        $top_selector = "0";
        $view->setVar("top", $top_selector);
        $view->setVar("date_debut", "");
        $view->setVar("date_fin", "");

        return $view;
    }

    /**
     * Redirige vers la page d'accueil.
     *
     * @return View Vue de la page d'accueil.
     */
    public function redirectionAcceuil()
    {
        return new View("views/acceuil");
    }

    /**
     * Redirige vers la page des notes de frais.
     *
     * @return View Vue de la page des notes de frais.
     */
    public function redirectionNoteFrais()
    {
        return new View("views/affichageListeNoteFrais");
    }

    /**
     * Redirige vers la page des fournisseurs.
     *
     * @return View Vue de la page des fournisseurs.
     */
    public function redirectionFournisseurs()
    {
        return new View("views/affichageListeFournisseurs");
    }
}