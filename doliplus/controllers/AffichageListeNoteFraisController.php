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

use DateTime;
use Exception;
use services\NotesDeFraisService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur pour gérer l'affichage et les opérations liées aux notes de frais.
 */
class AffichageListeNoteFraisController
{
    private $notesDeFraisService;

    /**
     * Constructeur pour initialiser le service des notes de frais.
     *
     * @param NotesDeFraisService $notesDeFraisService Service pour gérer les données des notes de frais.
     */
    public function __construct(NotesDeFraisService $notesDeFraisService) {
        $this->notesDeFraisService = $notesDeFraisService;
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
     * Affiche la liste des notes de frais.
     *
     * @return View Vue contenant la liste des notes de frais.
     */
    public function affichageListeNoteFrais() {
        // Vérifie la session utilisateur
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Récupère les notes de frais via le service
        $listeNotes = $this->notesDeFraisService->getNotesDeFrais();

        // Passe les données à la vue
        $view = new View("views/affichageListeNoteFrais");
        $view->setVar('listeNotes', $listeNotes);
        return $view;
    }

    /**
     * Recherche des notes de frais en fonction des critères fournis.
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
        $refNote = HttpHelper::getParam('ref_note');
        $crea_note = HttpHelper::getParam('crea_note');
        $valid_note = HttpHelper::getParam('valid_note');
        $date_creation = HttpHelper::getParam('date_creation');
        $date_fin = HttpHelper::getParam('date_fin');
        $paye = HttpHelper::getParam('paye');
        $montant = HttpHelper::getParam('montant');
        $type_frais = HttpHelper::getParam('type_frais');

        // Récupère les notes de frais correspondant aux critères
        $listeNotes = $this->notesDeFraisService->getNotesDeFraisAvecCriteres(
            $refNote, $crea_note, $valid_note, $date_creation, $date_fin, $paye, $montant, $type_frais
        );

        // Passe les données et les critères à la vue
        $view = new View("views/affichageListeNoteFrais");
        $view->setVar("listeNotes", $listeNotes);
        $view->setVar("ref_note", $refNote);
        $view->setVar("crea_note", $crea_note);
        $view->setVar("valid_note", $valid_note);
        $view->setVar("date_creation", $date_creation);
        $view->setVar("date_fin", $date_fin);
        $view->setVar("paye", $paye);
        $view->setVar("montant", $montant);
        $view->setVar("type_frais", $type_frais);

        return $view;
    }

    /**
     * Réinitialise les filtres de recherche et affiche la liste par défaut des notes de frais.
     *
     * @return View Vue contenant la liste par défaut des notes de frais.
     */
    public function remiseAZero()
    {
        // Vérifie la session utilisateur
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Récupère la liste par défaut des notes de frais
        $listeNotes = $this->notesDeFraisService->getNotesDeFrais();

        // Passe les données à la vue et réinitialise les champs
        $view = new View("views/affichageListeNoteFrais");
        $view->setVar("listeNotes", $listeNotes);
        $view->setVar("ref_note", "");
        $view->setVar("crea_note", "");
        $view->setVar("valid_note", "");
        $view->setVar("date_creation", "");
        $view->setVar("date_fin", "");
        $view->setVar("paye", "");
        $view->setVar("montant", "");
        $view->setVar("type_frais", "");

        return $view;
    }

    /**
     * Affiche une liste vide des notes de frais.
     *
     * @return View Vue contenant une liste vide.
     */
    public function affichageListeNoteFraisVide() {
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }
        return new View("views/affichageListeNoteFrais");
    }

    /**
     * Affiche une analyse sectorielle des notes de frais.
     *
     * @return View Vue contenant l'analyse sectorielle.
     */
    public function affichageSectorielNotesFrais() {
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }
        return new View("views/affichageSectorielNotesFrais");
    }

    /**
     * Affiche une analyse de l'évolution des notes de frais.
     *
     * @return View Vue contenant l'analyse de l'évolution.
     */
    public function affichageEvolutionNotesFrais() {
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }
        return new View("views/affichageEvolutionNotesFrais");
    }

    public function redirectionAcceuil() {
        return new View("views/acceuil");
    }

    public function redirectionNoteFrais() {
        return new View("views/affichageListeNoteFrais");
    }

    public function redirectionFournisseurs() {
        return new View("views/affichageListeFournisseurs");
    }


    public function getNotesFraisByType() {
        session_start();
        // Check authentication
        if (!isset($_SESSION['cleApi']) || empty($_SESSION['cleApi'])) {
            $this->jsonResponse([]);
            return;
        }

        // Get date parameters from POST
        $dateDebut = $_POST['dateDebut'] ?? null;
        $dateFin = $_POST['dateFin'] ?? null;

        if (!$dateDebut || !$dateFin) {
            $this->jsonResponse([]);
            return;
        }

        try {
            $typeStats = $this->notesDeFraisService->getNotesFraisByType($dateDebut, $dateFin);
            $this->jsonResponse($typeStats);
        } catch (Exception $e) {
            $this->jsonResponse([]);
        }
    }


    public function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function getNotesFraisByMonth() {
        session_start();

        $annee = isset($_POST['annee']) ? intval($_POST['annee']) : date('Y');
        $compareLastYear = isset($_POST['compareLastYear']) && $_POST['compareLastYear'] === '1';

        try {
            $result = $this->notesDeFraisService->getNotesFraisByMonth($annee, $compareLastYear);
            $this->jsonResponse($result);
        } catch (Exception $e) {
            $this->jsonResponse([]);
        }
    }

    public function getNotesFraisByDay() {
        session_start();

        $annee = isset($_POST['annee']) ? intval($_POST['annee']) : date('Y');
        $mois = isset($_POST['mois']) ? intval($_POST['mois']) : date('m');
        $compareLastYear = isset($_POST['compareLastYear']) && $_POST['compareLastYear'] === '1';

        try {
            $result = $this->notesDeFraisService->getNotesFraisByDay($annee, $mois, $compareLastYear);
            $this->jsonResponse($result);
        } catch (Exception $e) {
            $this->jsonResponse([]);
        }
    }
    public function getStatisticsData() {
        session_start();
        // Check authentication
        if (!isset($_SESSION['cleApi']) || empty($_SESSION['cleApi'])) {
            $this->jsonResponse([]);
            return;
        }

        // Get date parameters from POST or set defaults for the current year
        $dateDebut = $_POST['dateDebut'] ?? date('Y-01-01');
        $dateFin = $_POST['dateFin'] ?? date('Y-12-31');

        try {
            $typeStats = $this->notesDeFraisService->getStatisticsData($dateDebut, $dateFin);
            $this->jsonResponse($typeStats);
        } catch (Exception $e) {
            $this->jsonResponse([]);
        }
    }
}