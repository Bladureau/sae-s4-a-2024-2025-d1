<?php

namespace services;

use PHPUnit\Framework\TestCase;

class FournisseursServiceTest extends TestCase
{
    private $fournisseursService;
    private $apiServiceMock;

    protected function setUp(): void
    {
        // Etant donné que la classe FournisseursService dépend de la classe AppelApiService, nous allons la mocker
        $this->apiServiceMock = $this->createMock(AppelApiService::class);
        // Nous instancions la classe FournisseursService en lui passant le mock de AppelApiService
        $this->fournisseursService = new FournisseursService($this->apiServiceMock);
        // Et nous simulons aussi la clé API
        $_SESSION['cleApi'] = "fake_api_key"; // Simule la clé API


    }

    /**
     * @test
     * Test Get Fournisseurs Nominal
     * @description Test de la méthode getFournisseurs() avec des données nominales
     */
    public function testGetFournisseursNominal()
    {
        // Etant donné des données de test
        $mockData = [
            ['fournisseur' => '1', 'array_options' => ['options_chiffredaffaire' => '5000:EUR']],
            ['fournisseur' => '1', 'array_options' => ['options_chiffredaffaire' => '10000:EUR']],
            ['fournisseur' => '0', 'array_options' => ['options_chiffredaffaire' => '20000:EUR']], // 0 Donc ce n'est pas un fournisseur
        ];
        // Etant donné que la méthode appelAPIAvecCle du mock retourne les données de test
        $this->apiServiceMock->method('appelAPIAvecCle')->willReturn($mockData);

        // Quand la méthode getFournisseurs est appelée
        $result = $this->fournisseursService->getFournisseurs();

        // Alors le résultat doit être un tableau de 2 fournisseurs
        // ('fournisseur' => '0' ne compte pas comme un fournisseur car 0)
        $this->assertCount(2, $result);
        // Et le chiffre d'affaire du premier fournisseur doit être 10000
        $this->assertEquals(10000, $this->fournisseursService->getChiffreAffaire($result[0]));
        // Et le chiffre d'affaire du deuxième fournisseur doit être 5000
        $this->assertEquals(5000, $this->fournisseursService->getChiffreAffaire($result[1]));
    }

    /**
     * @test
     * Test Cas limite : Aucun fournisseur trouvé
     * @description Test de la méthode getFournisseurs() sans fournisseur trouvé
     */
    public function testGetFournisseursAucunFournisseur()
    {
        // Etant donné que la méthode appelAPIAvecCle du mock retourne un tableau vide
        $this->apiServiceMock->method('appelAPIAvecCle')->willReturn([]);

        // Quand la méthode getFournisseurs est appelée
        $result = $this->fournisseursService->getFournisseurs();

        // Alors le résultat doit être un tableau (et) vide
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     * Test Cas limite : Chiffre d'affaires non défini
     * @description Test de la méthode getFournisseurs() avec un fournisseur sans chiffre d'affaire
     */
    public function testGetFournisseursSansChiffreAffaire()
    {
        // Etant donné des données de test
        $mockData = [
            ['fournisseur' => '1', 'array_options' => []],
        ];
        // Ainsi que la méthode appelAPIAvecCle du mock retourne les données de test
        $this->apiServiceMock->method('appelAPIAvecCle')->willReturn($mockData);
        // Quand la méthode getFournisseurs est appelée
        $result = $this->fournisseursService->getFournisseurs();
        // Alors le résultat doit être un tableau de 1 fournisseur
        $this->assertCount(1, $result);
        // Et le chiffre d'affaire du fournisseur doit être 0
        $this->assertEquals(0, $this->fournisseursService->getChiffreAffaire($result[0]));
    }

    /**
     * @test
     * Test Cas d'erreur : Réponse invalide
     * @description Test de la méthode getFournisseurs() avec une réponse invalide
     */
    public function testGetFournisseursReponseInvalide()
    {
        // Etant donné que la méthode appelAPIAvecCle du mock retourne null
        $this->apiServiceMock->method('appelAPIAvecCle')->willReturn(null);

        // Quand la méthode getFournisseurs est appelée
        $result = $this->fournisseursService->getFournisseurs();

        // Alors le résultat doit être un tableau (et) vide
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
    /**
     * @test
     * Test avec l'API réelle
     * @description Test de la méthode getFournisseurs() avec l'API réelle
     */
    public function testGetFournisseursAvecApiReelle()
    {
        // Nous instancions la classe AppelApiService réelle
        $this->apiService = new AppelApiService();
        // Nous instancions la classe FournisseursService en lui passant l'instance réelle de AppelApiService
        $this->fournisseursService = new FournisseursService($this->apiService);
        // Et nous simulons aussi la clé API
        $_SESSION['cleApi'] = "syVM68G8LXWT2qemaR2874LBvT28stzs"; // Remplacez par votre clé API
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand la méthode getFournisseurs est appelée
        $result = $this->fournisseursService->getFournisseurs();

        // Alors le résultat doit être un tableau (et) non vide
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $palmares = array_map(function ($fournisseur) {
            return $this->fournisseursService->getChiffreAffaire($fournisseur);
        }, $result);

        $CA1 = $palmares[0];
        $CA2 = $palmares[1];

        // Et les fournisseurs doivent être triés par chiffre d'affaire décroissant
        // Le CA du premier fournisseur doit être supérieur ou égal au CA du deuxième fournisseur
        $this->assertGreaterThanOrEqual($CA2, $CA1, "Le CA du premier fournisseur devrait être 
        supérieur ou égal à celui du deuxième");
    }

    /**
     * @test
     * Test avec l'API réelle
     * @description Test de la méthode getFournisseurs() avec l'API réelle mais aucune clé API
     */
    public function testGetFournisseursAvecApiReelleSansCleApi(){
        session_start();
        $_SESSION['cleApi'] = null; // Assurez-vous que la clé API est absente
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";

        // Nous instancions la classe AppelApiService réelle
        $this->apiService = new AppelApiService();
        // Nous instancions la classe FournisseursService en lui passant l'instance réelle de AppelApiService
        $this->fournisseursService = new FournisseursService($this->apiService);

        // Quand la méthode getFournisseurs est appelée
        $result = $this->fournisseursService->getFournisseurs();

        // Alors le résultat doit être un tableau (et) vide
        $this->assertIsArray($result);
        $this->assertEmpty($result);

    }

    /**
     * @test
     * Test avec l'API réelle
     * @description Test de la méthode getPalmaresFournisseurs() avec l'API réelle
     */
    public function testGetPalmaresFournisseursAvecApiReelle()
    {
        // Nous instancions la classe AppelApiService réelle
        $this->apiService = new AppelApiService();
        // Nous instancions la classe FournisseursService en lui passant l'instance réelle de AppelApiService
        $this->fournisseursService = new FournisseursService($this->apiService);
        // Et nous simulons aussi la clé API
        $_SESSION['cleApi'] = "syVM68G8LXWT2qemaR2874LBvT28stzs"; // Remplacez par votre clé API
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";

        // Définir les dates de début et de fin pour le test
        $dateDebut = "2025-01-01";
        $dateFin = "2025-12-31";

        // Quand la méthode getPalmaresFournisseurs est appelée
        $result = $this->fournisseursService->getPalmaresFournisseurs($dateDebut, $dateFin);

        // Alors le résultat doit être un tableau (et) non vide
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

}

