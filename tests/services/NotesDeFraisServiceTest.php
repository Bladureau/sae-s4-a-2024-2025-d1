<?php

namespace services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class NotesDeFraisServiceTest extends TestCase
{
    //   private NotesDeFraisService $notesDeFraisService;
    //   private MockObject $apiServiceMock;

    private NotesDeFraisService $service;

    private AppelApiService $apiService;

    protected function setUp(): void
    {
        // $this->apiServiceMock = $this->createMock(AppelApiService::class);
        // $this->notesDeFraisService = new NotesDeFraisService($this->apiServiceMock);
        // Etant une classe Api Service pour les appels API
        $this->apiService = new AppelApiService();
        // Ainsi que la classe NotesDeFraisService
        $this->service = new NotesDeFraisService($this->apiService);
        session_start();
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";

    }

    /**
     * @test
     * Test de la méthode getNotesDeFrais avec une clé API valide
     * @description On vérifie que la méthode retourne un tableau de notes de frais
     */
    public function testGetNotesDeFraisIntegration()
    {
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand la méthode getNotesDeFrais est appelée
        $response = $this->service->getNotesDeFrais();

        // Alors le resultat doit être un tableau non vide
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);
    }

    /**
     * @test
     * Test de la méthode getNotesDeFrais avec une clé API invalide
     * @description On vérifie que la méthode retourne un tableau vide
     */
    public function testGetNotesDeFraisWithInvalidApiKey(){
        // Etant donné que la clé API est invalide
        $_SESSION['cleApi'] = 'invalid';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand la méthode getNotesDeFrais est appelée
        $response = $this->service->getNotesDeFrais();

        // Alors le resultat doit être un tableau vide
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * @test
     * Test de la méthode getNotesDeFrais avec une clé API qui na pas les droits
     * @description On vérifie que la méthode retourne un tableau vide
     */
    public function testGetNotesDeFraisWithNoRights(){
        // Etant donné que la clé API n'a pas les droits nécessaires pour accéder aux notes de frais
        $_SESSION['cleApi'] = '3ecI563pA6lN2MRIIhNkn8Ca3c01ByGa';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand la méthode getNotesDeFrais est appelée
        $response = $this->service->getNotesDeFrais();

        // Alors le resultat doit être un tableau vide
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * @test
     * Test de la méthode getNotesDeFrais avec une clé API qui non définie
     * @description On vérifie que la méthode retourne un tableau vide
     */
    public function testGetNotesDeFraisWithNoApiKey(){
        // Etant donné que la clé API n'est pas définie
        unset($_SESSION['cleApi']);

        // Quand la méthode getNotesDeFrais est appelée
        $response = $this->service->getNotesDeFrais();

        // Alors le resultat doit être un tableau vide
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * @test
     * Test d'intégration de la méthode getNotesDeFraisAvecCriteres avec la vraie API
     * @description On vérifie que la méthode retourne les notes de frais filtrées correctement
     */
    public function testGetNotesDeFraisAvecAucunCriteresIntegration()
    {
        session_start();
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand la méthode getNotesDeFraisAvecCriteres est appelée avec des critères spécifiques
        $response = $this->service->getNotesDeFraisAvecCriteres('',
            '', '', '', '',
            '', '', '');

        // Alors le resultat doit être un tableau non vide
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);

        // Vous pouvez ajouter d'autres assertions pour vérifier les données retournées
        $this->assertEquals('ER2502-0001', $response[0]['ref']);
        $this->assertEquals('Lucas dupond', $response[0]['user_author_infos']);
    }
    /**
     * @test
     * Test d'intégration de la méthode getNotesDeFraisAvecCriteres avec la vraie API
     * @description On vérifie que la méthode retourne les notes de frais filtrées correctement
     */
    public function testGetNotesDeFraisAvecCriteresInvalideIntegration()
    {
        session_start();
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand la méthode getNotesDeFraisAvecCriteres est appelée avec des critères spécifiques
        $response = $this->service->getNotesDeFraisAvecCriteres('1234',
            'test', 'test', '02/02/2025', '02/02/2025',
            '0', '120', 'Lunch');

        // Alors le resultat doit être un tableau non vide
        $this->assertIsArray($response);
        $this->assertEmpty($response);

    }


    /**
     * @test
     * Test d'intégration de la méthode getNotesDeFraisAvecCriteres avec la vraie API, mais sans clé API
     * @description On vérifie que la méthode retourne un tableau vide
     */
    public function testGetNotesDeFraisAvecAucunCriteresIntegrationWithoutApiKey()
    {
        session_start();
        // Etant donné que la clé API n'est pas définie
        unset($_SESSION['cleApi']);


        // Quand la méthode getNotesDeFraisAvecCriteres est appelée avec des critères spécifiques
        $response = $this->service->getNotesDeFraisAvecCriteres('',
            '', '', '', '',
            '', '', '');

        // Alors le resultat doit être un tableau vide
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * @test
     * Test d'intégration de la méthode getNotesDeFraisAvecCriteres avec la vraie API,
     * mais clé qui n'a pas les droits
     * @description On vérifie que la méthode retourne un tableau vide
     */
    public function testGetNotesDeFraisAvecAucunCriteresIntegrationWithoutRights()
    {
        session_start();
        // Etant donné que la clé API n'a pas les droits nécessaires
        $_SESSION['cleApi'] = '3ecI563pA6lN2MRIIhNkn8Ca3c01ByGa';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand la méthode getNotesDeFraisAvecCriteres est appelée avec des critères spécifiques
        $response = $this->service->getNotesDeFraisAvecCriteres('',
            '', '', '', '',
            '', '', '');

        // Alors le resultat doit être un tableau vide
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }
    /**
     * @test
     * Test d'intégration de la méthode getNotesDeFraisAvecCriteres avec la vraie API,
     * mais cle qui n'existe pas
     * @description On vérifie que la méthode retourne un tableau vide
     */
    public function testGetNotesDeFraisAvecAucunCriteresIntegrationWithInvalidApiKey()
    {
        session_start();
        // Etant donné que la clé API n'existe pas
        $_SESSION['cleApi'] = 'invalid';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Quand la méthode getNotesDeFraisAvecCriteres est appelée avec des critères spécifiques
        $response = $this->service->getNotesDeFraisAvecCriteres('',
            '', '', '', '',
            '', '', '');

        // Alors le resultat doit être un tableau vide
        $this->assertIsArray($response);
        $this->assertEmpty($response);
    }

    /**
     * @test
     * Test de la méthode getNotesFraisByType avec des dates valides
     * @description On vérifie que la méthode retourne un tableau de statistiques par type
     */
    public function testGetNotesFraisByTypeWithValidDates()
    {
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Pour une période d'une année (pour avoir des données)
        $dateDebut = date('2025-01-01', strtotime('-1 year'));
        $dateFin = date('2025-12-31');

        // Quand la méthode getNotesFraisByType est appelée avec des dates valides
        $response = $this->service->getNotesFraisByType($dateDebut, $dateFin);

        // Alors le resultat doit être un tableau non vide
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);

        // Et chaque élément doit avoir la structure attendue
        $this->assertArrayHasKey('type', $response[0]);
        $this->assertArrayHasKey('montant', $response[0]);
        $this->assertArrayHasKey('nombre', $response[0]);

        // Et ils sont triés par montant décroissant
        if (count($response) > 1) {
            $this->assertGreaterThanOrEqual($response[1]['montant'], $response[0]['montant'],
                "Les types devraient être triés par montant décroissant");
        }
    }
    /**
     * @test
     * Test de comparaison des montants par type
     * @description On vérifie que le montant total d'un type est supérieur à un autre
     */
    public function testCompareNotesFraisTypesMontants()
    {
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Pour une période d'une année (pour avoir des données)
        $dateDebut = date('2025-01-01', strtotime('-1 year'));
        $dateFin = date('2025-12-31');

        // Quand on récupère les statistiques par type
        $typeStats = $this->service->getNotesFraisByType($dateDebut, $dateFin);

        // S'assure qu'il y a au moins 2 types pour comparer
        if (count($typeStats) >= 2) {
            // Alors le montant du premier type devrait être supérieur au second
            // (puisqu'ils sont triés par montant décroissant)
            $this->assertGreaterThan(
                $typeStats[1]['montant'],
                $typeStats[0]['montant'],
                "Le montant du type le plus élevé devrait être supérieur au second type"
            );
        } else {
            // Si moins de 2 types, marquer le test comme incomplet mais pas échoué
            $this->markTestSkipped("Pas assez de types différents pour comparer");
        }
    }
    /**
     * @test
     * Test de la méthode getNotesFraisByType2 avec des dates valides
     * @description On vérifie que la méthode retourne un tableau de statistiques par type
     */
    public function testGetNotesFraisByType2WithValidDates()
    {
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Pour une période d'une année (pour avoir des données)
        $dateDebut = date('2025-01-01', strtotime('-1 year'));
        $dateFin = date('2025-12-31');

        // Quand la méthode getNotesFraisByType2 est appelée avec des dates valides
        $response = $this->service->getNotesFraisByType2($dateDebut, $dateFin);

        // Alors le resultat doit être un tableau non vide
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);

    }

    /**
     * @test
     * Test de la méthode getNotesFraisByMonth
     * @description On vérifie que la méthode retourne les montants et nombres de notes de frais par mois
     */
    public function testGetNotesFraisByMonth()
    {
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Mock des données de notes de frais
        $mockData = [
            ['date_debut' => strtotime('2024-01-15'), 'total_ttc' => 100],
            ['date_debut' => strtotime('2024-02-20'), 'total_ttc' => 200],
            ['date_debut' => strtotime('2024-02-25'), 'total_ttc' => 150],
            ['date_debut' => strtotime('2023-02-20'), 'total_ttc' => 300],
        ];

        // Mock de la méthode getNotesDeFrais pour retourner les données mockées
        $this->apiServiceMock = $this->createMock(AppelApiService::class);
        $this->apiServiceMock->method('appelAPIAvecCle')->willReturn($mockData);
        $this->service = new NotesDeFraisService($this->apiServiceMock);

        // Quand la méthode getNotesFraisByMonth est appelée pour l'année 2024
        $response = $this->service->getNotesFraisByMonth(2024, true);

        // Alors le resultat doit être un tableau avec les montants et nombres par mois
        $this->assertIsArray($response);
        // Et pas vide
        $this->assertNotEmpty($response);
    }

    /**
     * @test
     * Test de la méthode getNotesFraisByDay
     * @description On vérifie que la méthode retourne les montants et nombres de notes de frais par jour
     */
    public function testGetNotesFraisByDay()
    {
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Mock des données de notes de frais
        $mockData = [
            ['date_debut' => strtotime('2024-02-01'), 'total_ttc' => 100],
            ['date_debut' => strtotime('2024-02-15'), 'total_ttc' => 200],
            ['date_debut' => strtotime('2024-02-15'), 'total_ttc' => 150],
            ['date_debut' => strtotime('2023-02-20'), 'total_ttc' => 300],
        ];

        // Mock de la méthode getNotesDeFrais pour retourner les données mockées
        $this->apiServiceMock = $this->createMock(AppelApiService::class);
        $this->apiServiceMock->method('appelAPIAvecCle')->willReturn($mockData);
        $this->service = new NotesDeFraisService($this->apiServiceMock);

        // Quand la méthode getNotesFraisByDay est appelée pour février 2024
        $response = $this->service->getNotesFraisByDay(2024, 2, true);

        // Alors le resultat doit être un tableau avec les montants et nombres par jour
        $this->assertIsArray($response);
        $this->assertArrayHasKey('currentYear', $response);
        $this->assertArrayHasKey('previousYear', $response);

        // Vérification des montants et nombres pour l'année courante
        $this->assertEquals(100, $response['currentYear']['montants'][0]); // 1er février
        $this->assertEquals(350, $response['currentYear']['montants'][14]); // 15 février
        $this->assertEquals(1, $response['currentYear']['nombres'][0]); // 1er février
        $this->assertEquals(2, $response['currentYear']['nombres'][14]); // 15 février

        // Vérification des montants et nombres pour l'année précédente
        $this->assertEquals(0, $response['previousYear']['montants'][0]); // 1er février
        $this->assertEquals(300, $response['previousYear']['montants'][19]); // 20 février
        $this->assertEquals(0, $response['previousYear']['nombres'][0]); // 1er février
        $this->assertEquals(1, $response['previousYear']['nombres'][19]); // 20 février
    }

    /**
     * @test
     * Test de la méthode getStatisticsData
     * @description On vérifie que la méthode retourne les données statistiques correctes
     */
    public function testGetStatisticsData()
    {
        // Etant donné que la clé API est valide
        $_SESSION['cleApi'] = 'syVM68G8LXWT2qemaR2874LBvT28stzs';
        $_SESSION['url'] = "http://dolibarr.iut-rodez.fr/G2024-41-SAE";


        // Mock des données de notes de frais
        $mockData = [
            ['date_debut' => strtotime('2024-01-15'), 'total_ttc' => 100,
                'lines' => [['type_fees_libelle' => 'Transport', 'total_ttc' => 100]]],
            ['date_debut' => strtotime('2024-02-20'), 'total_ttc' => 200,
                'lines' => [['type_fees_libelle' => 'Repas', 'total_ttc' => 200]]],
            ['date_debut' => strtotime('2024-02-25'), 'total_ttc' => 150,
                'lines' => [['type_fees_libelle' => 'Transport', 'total_ttc' => 150]]],
            ['date_debut' => strtotime('2024-02-20'), 'total_ttc' => 300,
                'lines' => [['type_fees_libelle' => 'Hébergement', 'total_ttc' => 300]]],
        ];

        // Mock de la méthode getNotesDeFrais pour retourner les données mockées
        $this->apiServiceMock = $this->createMock(AppelApiService::class);
        $this->apiServiceMock->method('appelAPIAvecCle')->willReturn($mockData);
        $this->service = new NotesDeFraisService($this->apiServiceMock);

        // Quand la méthode getStatisticsData est appelée
        $response = $this->service->getStatisticsData("2024-01-01", "2024-12-31");

        // Alors le resultat doit être un tableau non vide
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);

        // Vérification des données retournées
        $this->assertArrayHasKey('libelle', $response[0]);
        $this->assertArrayHasKey('total_ttc', $response[0]);
        $this->assertArrayHasKey('nombre', $response[0]);
    }

}
