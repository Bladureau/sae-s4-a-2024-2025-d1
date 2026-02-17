<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: vous pouvez le redistribuer et/ou le modifier
 *     selon les termes de la GNU Affero General Public License tels que publiés
 *     par la Free Software Foundation, soit la version 3 de la licence, soit
 *     (à votre choix) toute version ultérieure.
 *
 *     Ce programme est distribué dans l'espoir qu'il sera utile,
 *     mais SANS AUCUNE GARANTIE; sans même la garantie implicite de
 *     QUALITÉ MARCHANDE ou d'ADÉQUATION À UN USAGE PARTICULIER. Voir la
 *     GNU Affero General Public License pour plus de détails.
 *
 *     Vous devriez avoir reçu une copie de la GNU Affero General Public License
 *     avec ce programme. Si ce n'est pas le cas, voir <https://www.gnu.org/licenses/>.
 */

namespace controllers;

use services\FactureService;
use yasmf\View;

/**
 * Classe contrôleur par défaut
 */
class AffichageFactureController
{
    private $FactureService;

    /**
     * Constructeur de la classe AffichageFactureController
     *
     * @param FactureService $FactureService Service de gestion des factures
     */
    public function __construct(FactureService $FactureService) {
        $this->FactureService = $FactureService;
    }

    /**
     * Fonction pour afficher la liste des factures
     *
     * @return View Vue affichant la liste des factures
     */
    public function affichageFacture() {
        $listeFacture = $this->FactureService->getFactures();
        // Création de la vue avec le tableau des factures
        $view = new View("views/affichageListeFacture");
        $view->setVar('listeFacture', $listeFacture);
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