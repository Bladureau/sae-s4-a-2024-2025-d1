<?php

namespace tests\services;
use PHPUnit\Framework\TestCase;
use services\FactureService;
use services\AppelApiService;

class FactureServiceIntegrationTest extends TestCase
{
    private $factureService;

    protected function setUp(): void
    {
        $apiService = new AppelApiService();
        $this->factureService = new FactureService($apiService);
    }

    public function testGetFacturesIntegration()
    {
        session_start();
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $result = $this->factureService->getFactures();
        $this->assertIsArray($result);
    }

    public function testGetFacturesAvecCriteresIntegration()
    {
        session_start();
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";
        $result = $this->factureService->getFacturesAvecCriteres('Test', '', null, '', '', '', '', '', '', '', '');
        $this->assertIsArray($result);
    }
}