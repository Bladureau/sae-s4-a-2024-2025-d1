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

use services\FactureService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Classe contrôleur pour l'affichage de la liste des fournisseurs
 */
class AffichageListeFournisseursController
{
    private $FactureService;

    /**
     * Constructeur de la classe AffichageListeFournisseursController
     *
     * @param FactureService $FactureService Service de gestion des factures
     */
    public function __construct(FactureService $FactureService)
    {
        $this->FactureService = $FactureService;
    }

    /**
     * Vérifie si la session est valide
     *
     * @return View|null Vue de connexion si la session est invalide, null sinon
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
     * Affiche la liste des fournisseurs
     *
     * @return View Vue affichant la liste des fournisseurs
     */
    public function afficherFournisseurs()
    {
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Récupération des factures fournisseurs
        $listeFacture = $this->FactureService->getFactures();
        $view = new View("views/affichageListeFournisseurs");
        // Passage des données à la vue
        $view->setVar("listeFacture", $listeFacture);
        return $view;
    }

    /**
     * Recherche des fournisseurs selon les critères spécifiés
     *
     * @return View Vue affichant la liste des fournisseurs filtrée
     */
    public function recherche()
    {

        // Vérifie que la session est valide
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Récupération des champs du formulaire avec getParam
        $nom = HttpHelper::getParam('nom_soc');
        $libelle = HttpHelper::getParam('lib_soc');
        $paye = in_array(HttpHelper::getParam('paye'), ['0', '1']) ? HttpHelper::getParam('paye') : null;
        $montant = HttpHelper::getParam('montant');
        $numFacture = HttpHelper::getParam('num_facture');
        $ref_fournisseur = HttpHelper::getParam('ref_fournisseur');
        $date_creation = HttpHelper::getParam('date_creation');
        $date_echeance = HttpHelper::getParam('date_echeance');
        $type_paiement = HttpHelper::getParam('type_paiement');
        $nom_fournisseur = HttpHelper::getParam('nom_fournisseur');
        $ville_fournisseur = HttpHelper::getParam('ville_fournisseur');

        // Récupération des factures fournisseurs avec les critères
        $listeFacture = $this->FactureService->getFacturesAvecCriteres($nom, $libelle, $paye, $montant, $numFacture,
            $ref_fournisseur, $date_creation, $date_echeance, $type_paiement, $nom_fournisseur, $ville_fournisseur);

        // Récupération des documents liés aux factures
        foreach ($listeFacture as &$facture) {
            if (isset($facture['ref'])) {
                $facture['documents_lies'] = $this->FactureService->getDocumentsLies($facture['ref']);
            }
        }

        // Création de la vue
        $view = new View("views/affichageListeFournisseurs");

        // Passage des données à la vue
        $view->setVar("listeFacture", $listeFacture);
        $view->setVar("lib_soc", $libelle);
        $view->setVar("nom_soc", $nom);
        $view->setVar("paye", $paye);
        $view->setVar("montant", $montant);
        $view->setVar("num_facture", $numFacture);
        $view->setVar("ref_fournisseur", $ref_fournisseur);
        $view->setVar("date_creation", $date_creation);
        $view->setVar("date_echeance", $date_echeance);
        $view->setVar("type_paiement", $type_paiement);
        $view->setVar("nom_fournisseur", $nom_fournisseur);
        $view->setVar("ville_fournisseur", $ville_fournisseur);

        return $view;
    }

    /**
     * Réinitialise les filtres de recherche et affiche la liste des fournisseurs
     *
     * @return View Vue affichant la liste des fournisseurs avec les filtres réinitialisés
     */
    public function remiseAZero()
    {
        // Vérifie que la session est valide
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Récupération des factures fournisseurs
        $listeFacture = $this->FactureService->getFactures();

        // Récupération des documents liés aux factures
        foreach ($listeFacture as &$facture) {
            if (isset($facture['ref'])) {
                $facture['documents_lies'] = $this->FactureService->getDocumentsLies($facture['ref']);
            }
        }

        // Création de la vue
        $view = new View("views/affichageListeFournisseurs");
        $view->setVar("listeFacture", $listeFacture);

        // Remise à zéro des champs
        $view->setVar("nom_soc", "");
        $view->setVar("lib_soc", "");
        $view->setVar("paye", "");
        $view->setVar("montant", "");
        $view->setVar("num_facture", "");
        $view->setVar("ref_fournisseur", "");
        $view->setVar("date_creation", "");
        $view->setVar("date_echeance", "");
        $view->setVar("type_paiement", "");
        $view->setVar("nom_fournisseur", "");
        $view->setVar("ville_fournisseur", "");

        return $view;
    }

    /**
     * Affiche la vue de début pour la liste des fournisseurs
     *
     * @return View Vue de la liste des fournisseurs
     */
    public function afficherVueDebut()
    {
        // Vérifie que la session est valide
        $sessionCheck = $this->checkSession();
        if ($sessionCheck !== null) {
            return $sessionCheck;
        }

        // Création de la vue
        $view = new View("views/affichageListeFournisseurs");
        return $view;
    }

    /**
     * Redirection vers la page d'accueil
     *
     * @return View Vue de la page d'accueil
     */
    public function redirectionAcceuil() {
        return new View("views/acceuil");
    }

    /**
     * Redirection vers la page des notes de frais
     *
     * @return View Vue de la page des notes de frais
     */
    public function redirectionNoteFrais() {
        return new View("views/affichageListeNoteFrais");
    }

    /**
     * Redirection vers la page des fournisseurs
     *
     * @return View Vue de la page des fournisseurs
     */
    public function redirectionFournisseurs() {
        return new View("views/affichageListeFournisseurs");
    }
}