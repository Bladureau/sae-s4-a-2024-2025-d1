<?php

namespace services;

use PHPUnit\Framework\TestCase;

class AppelApiServiceTest extends TestCase
{
    private AppelApiService $apiService;
    private string $validApiKey = "syVM68G8LXWT2qemaR2874LBvT28stzs";
    private string $invalidApiKey = "3ecI563pA6lN2MRIIhNkn8Ca3c01ByGa";
    private string $apiBaseUrl = "http://dolibarr.iut-rodez.fr/G2024-41-SAE/htdocs/api/index.php/"; // Remplacer par l'URL de votre API

    protected function setUp(): void
    {
        // Etant donnée un service api permettant de faire des appels à une API
        $this->apiService = new AppelApiService();
    }

    /**
     * @test
     * @description "Teste l'appel à l'API avec une clé valide pour les tiers"
     */
    public function testValidApiKeyThirdparties()
    {
        // Quand on appelle l'API avec une clé valide
        $response = $this->apiService->appelAPIAvecCle($this->apiBaseUrl . "thirdparties", $this->validApiKey);

        // Alors l'API devrait renvoyer des données
        $this->assertNotEmpty($response, "L'API devrait renvoyer des données valides.");
    }

    /**
     * @test
     * @description "Teste l'appel à l'API avec une clé valide pour les notes de frais"
     */

    public function testValidApiKeyExpenseReports()
    {
        $response = $this->apiService->appelAPIAvecCle($this->apiBaseUrl . "expensereports", $this->validApiKey);

        $this->assertNotEmpty($response, "L'API devrait renvoyer des données valides.");
    }

    /** @test
     * @description "Teste l'appel à l'API avec une clé invalide"
     */
    public function testInvalidApiKey()
    {
        // Quand on appelle l'API avec une clé invalide
        $response = $this->apiService->appelAPIAvecCle($this->apiBaseUrl . "thirdparties", $this->invalidApiKey);

        // Alors l'API ne devrait rien renvoyer
        $this->assertEmpty($response, "L'API ne devrait rien renvoyer avec une clé invalide.");

    }

    /**
     * @test
     * @description "Teste l'appel à l'API avec un endpoint invalide
     */
    public function testInvalidEndpoint()
    {
        // Quand on appelle l'API avec un endpoint invalide
        $response = $this->apiService->appelAPIAvecCle($this->apiBaseUrl . "invalid_endpoint", $this->validApiKey);

        // Alors l'API ne devrait rien renvoyer
        $this->assertEmpty($response, "L'API ne devrait rien renvoyer pour un endpoint invalide.");
    }

    /**
     * @test
     * @description "Teste l'appel à l'API avec une URL invalide
     */
    public function testInvalidUrl()
    {
        // Quand on appelle l'API avec une URL invalide
        $response = $this->apiService->appelAPIAvecCle("http://invalid-url", $this->validApiKey);

        //
        $this->assertEmpty($response, "L'appel à une URL invalide devrait retourner une réponse vide.");
    }
}
