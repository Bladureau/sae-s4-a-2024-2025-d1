<?php

namespace services;

use PHPUnit\Framework\TestCase;

class FactureServiceTest extends TestCase
{
   private $factureService;
   private $apiServiceMock;

   private $factureServiceMock;
//
   protected function setUp(): void
   {
       $this->apiServiceMock = $this->createMock(AppelApiService::class);
       // Service FactureService
       $this->factureServiceMock = new FactureService($this->apiServiceMock);
       //Etant donné un service facture
       $this->factureService = new FactureService( new AppelApiService());

   }

    /**
     * @test
     * Test cas nominal de getFactures
     * @description Test cas nominal de getFactures
     */
    public function testGetFacturesNominal()
    {
        $this->factureService = new FactureService(new AppelApiService());
        session_start();
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] =    "http://dolibarr.iut-rodez.fr/G2024-41-SAE/htdocs/api/index.php/supplierinvoices?sortfield=t.rowid&";
        $this->factureService->apiUrl = $_SESSION['url'];


        // Quand j'appelle getFactures
        $result = $this->factureService->getFactures();
        // Alors le resultat est un tableau
        $this->assertIsArray($result);
        // Et le tableau contient 8 éléments / n elements en fonction de l'api
        $this->assertCount(8, $result); // Ajuster en fonction de ce que contient l'api
        // ET le premier élément du tableau contient un total_ttc de 120, le deuxieme un total_ttc de 1320, ...
        $this->assertEquals(120, $result[0]['total_ttc']); // Ajuster en fonction de ce que contient l'api
        $this->assertEquals(1320, $result[1]['total_ttc']); // Ajuster en fonction de ce que contient l'api
    }

    /**
     * @test
     * Test cas limite de getFactures
     * @description Test cas limite de getFactures
     */
    public function testGetFacturesCleApiInvalide()
    {
        session_start();
        // Etant donnée un service (setup) et une clé api non valide
        $_SESSION['cleApi'] = 'non';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand j'apelle getFactures
        $result = $this->factureService->getFactures();

        // Alors le resultat il est vide
        $this->assertEmpty($result);
    }

    public function testGetFacturesCleApiNonAutorisee()
    {
        session_start();
        // Etant donnée un service (setup) et une clé api valide mais qui n'a pas les autorisations
        $_SESSION['cleApi'] = '3ecI563pA6lN2MRIIhNkn8Ca3c01ByGa';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";

        // Quand j'apelle getFactures
        $result = $this->factureService->getFactures();
        // Alors le resultat est vide
        $this->assertEmpty($result);
    }

    //-----------------A REVOIR CAR DOIT RENVOYER UN ELEMENT // TODO--------------------------
    public function testGetFacturesAvecCriteresWithRealApi(){
        date_default_timezone_set('Europe/Paris');
        session_start();
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";



        $result = $this->factureService->getFacturesAvecCriteres(
            'fournisseur1',
            'testtest',
            "1",
            '120',
            'SI2502-0001',
            'SU2502-00002',
            '21/02/2025',
            '25/02/2025',
            '4',
            null,
            null
        );
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    public function testGetFacturesAvecCriteresWithRealApiNoMatch(){
        session_start();
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        $result = $this->factureService->getFacturesAvecCriteres('Nonexistent', '', null,
            '', '', '', '', '', '',
            '', '');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
    /**
     * @test
     * Test avec aucune clé Api
     */
    public function testGetFacturesAvecCriteresSansCleApi()
    {
        session_start();
        // Etant donnée un service (setup) et une clé api non "vide"
        $_SESSION['cleApi'] = null;
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand j'apelle getFactures
        $result = $this->factureService->getFacturesAvecCriteres('Nonexistent', '', null,
            '', '', '', '', '', '',
            '', '');

        // Alors le resultat il est vide
        $this->assertEmpty($result);
    }
    public function testGetFacturesAvecCriteresWithAllFieldEmpty(){
        session_start();
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        $result = $this->factureService->getFacturesAvecCriteres('', '', '',
            '', '', '', '', '', '',
            '', '');

        $this->assertNotEmpty($result);
    }
    public function testGetDocumentsLies() {
        session_start();
        $_SESSION['cleApi'] = "syVM68G8LXWT2qemaR2874LBvT28stzs";
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";

        $result = $this->factureService->getDocumentsLies('SI2502-0006');
        $this->assertIsArray($result);
        $this->assertEquals(1, count($result));
    }

    public function testGetDocumentsLiesSansApiKey(){
        session_start();
        $_SESSION['cleApi'] = null;
        $result = $this->factureService->getDocumentsLies('FA2024-001');
        $this->assertEmpty($result);
    }

    public function testGetDocumentsLiesApiReturnsEmpty(){
        session_start();
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";

        $result = $this->factureService->getDocumentsLies('SU2502-00006');
        $this->assertEmpty($result);
    }

    public function testGetDocumentsLiesWithInvalidReference() {
        session_start();
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";

        $result = $this->factureService->getDocumentsLies('SU2502-00007');
        $this->assertEmpty($result);
    }
}
