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
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur pour gérer les opérations de connexion et déconnexion des utilisateurs.
 */
class ConnexionController
{
    private $connexionService;
    private $fournisseursService;

    /**
     * Constructeur pour initialiser les services nécessaires.
     *
     * @param ConnexionService $connexionService Service pour gérer les connexions API.
     * @param FournisseursService $fournisseursService Service pour gérer les données des fournisseurs.
     */
    public function __construct(ConnexionService $connexionService, FournisseursService $fournisseursService) {
        $this->fournisseursService = $fournisseursService;
        $this->connexionService = $connexionService;
    }

    /**
     * Gère la connexion de l'utilisateur.
     *
     * @return View Vue à afficher après la tentative de connexion.
     */
    public function getUser(): View {
        // Démarre la session
        session_start();

        // Récupère les paramètres envoyés via le formulaire
        $username = HttpHelper::getParam('username') ?: 'no name'; // Nom d'utilisateur par défaut si vide
        $login = HttpHelper::getParam('username'); // Identifiant de connexion
        $password = HttpHelper::getParam('password'); // Mot de passe
        $url = HttpHelper::getParam('new_url'); // URL de connexion

        // Si l'URL n'est pas fournie, on récupère une autre valeur possible
        if (empty($url)) {
            $url = HttpHelper::getParam('url');
        }

        // Tente de se connecter via l'API
        $cleApi = $this->connexionService->connexionAPI($login, $password, $url);

        // Si la clé API est vide ou invalide, on retourne à la vue de connexion
        if ($cleApi == "" || $cleApi == null) {
            // On repropose le login et l'URL si la connexion échoue
            $view = new View("views/connexion");
            $view->setVar('url', $_SESSION['cleApi']); // URL utilisée précédemment
            $view->setVar('username', $_SESSION['username']); // Nom d'utilisateur utilisé

            return $view;
        } else {
            // Si la connexion réussit, on récupère les fournisseurs
            $fournisseurs = $this->fournisseursService->getFournisseurs();

            // On prépare la vue d'accueil
            $view = new View("views/acceuil");
            $view->setVar("fournisseurs", $fournisseurs); // Liste des fournisseurs
            $_SESSION['username'] = $username; // Stocke le nom d'utilisateur dans la session
            $_SESSION['cleApi'] = $cleApi; // Stocke la clé API dans la session
            $view->setVar('username', $_SESSION['username']); // Passe le nom d'utilisateur à la vue
            $_SESSION['url'] = $url; // Stocke l'URL dans la session

            // Sauvegarde l'URL pour une utilisation future
            $this->connexionService->sauvegarderLien($url);

            return $view;
        }
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     *
     * @return View Vue de la page de connexion après déconnexion.
     */
    public function deconnexion(): View {
        // Détruit la session pour déconnecter l'utilisateur
        session_destroy();

        // Retourne la vue de connexion
        $view = new View("views/connexion");
        return $view;
    }
}