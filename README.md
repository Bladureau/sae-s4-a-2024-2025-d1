# Description du projet
## Contexte du projet

Une organisation a intégré l'outil DOLIBARR (version 17.0.4) dans son système d'information. Cependant, les utilisateurs ont identifié des lacunes, notamment dans les domaines décisionnels et statistiques. Deux groupes d'utilisateurs, les gestionnaires des achats et les gestionnaires des notes de frais des employés, ont exprimé des besoins spécifiques. De plus, l'interface de DOLIBARR n'est pas adaptée à une utilisation sur téléphone portable, nécessitant une interface plus conviviale et ciblée. Une équipe de développement a été sollicitée pour répondre à ces besoins.

## Objectifs du projet
Dans cette SAÉ, nous devions répondre à plusieurs objectifs :
1. Connexion a l'API de Dolibarr
2. Utilisation de l'API de Dolibarr
3. Création d'un site web en PHP/CSS/JS avec une architecture MVC et le framework Bootstrap et chart.js
4. Une couverture de test de 80% minimum avec PHPStan
5. Le site devra être herbégé sur un serveur web gratuit
6. La sécurité du site devra être assurée grâce au module 'Site Externe' de Dolibarr
7. Le site devra suivre la présentation des modules Dolibarr (couleurs, logo, etc.) et être responsive

## Fonctionnalités du site
1. Connexion d'un utilisateur référencé à l'API de Dolibarr
2. Les gestionnaires des notes de frais, ont fait trois demandes : 
    - Liste des notes de frais multi-critères
      - Affichage sous forme de liste d'un ou plusieurs employés
      - Période donnée
      - Plusieurs types (Frais kilométriques, Frais de repas, Frais d'hôtel, Frais de taxi, Frais de parking, Frais de péage, Frais de carburant)
      - Remboursées ou pas encore remboursées
    - Graphique d'évolution des notes de frais
      - Affichage de l'évolution des notes de frais d'un employé sur une période donnée
      - Avec éventuellement un comparatif avec la même période de l'année précédente
    - Diagramme sectoriel des notes de frais
      - Affichage des notes de frais d'un employé sous forme de diagramme sectoriel avec les différents types de frais
3. Les gestionnaires des achats, ont fait deux demandes :
    - Consultation de l'historique de factures passées chez un fournisseur.
    - L'utilisateur pourra, à l'aide d'une recherche multicritère (nom, numéro de téléphone, adresse…), rechercher un fournisseur et afficher son historique de factures avec possibilité d'afficher le détail des lignes (article, quantité, montant…).
    
    - Les éventuels fichiers joints à la facture devront pouvoir être téléchargés et affichés.
    - Palmarès fournisseur.
       - L'utilisateur pourra demander d'afficher le palmarès des fournisseurs par chiffre d'affaires de date à date avec sélection du nombre de fournisseurs affichés (top 10, top 20…).
       - Une représentation sous forme graphique est demandée en plus de la liste.

## Contributeurs de cette SAÉ :

| Nom prénom          | Adresse mail                      | Login GitHub      |
| ------------------- | --------------------------------- | ----------------- |
| Cazor--Bonnet Adrian| adrian.cazor--bonnet@iut-rodez.fr | CAZORBONNETAdrian |
| Chesnier Quentin    | quentin.chesnier@iut-rodez.fr     | qchesnier         |
| Juery Clément       | clement.juery@iut-rodez.fr        | cju3ry            |
| Ladureau Baptiste   | baptiste.ladureau@iut-rodez.fr    | Bladureau         |

## Rôles par itérations :

| Itération 0 (du 30/01/25 au 16/02/25) ||||
| -------------------- | ----------------- | -------------- | ------------------ |
| Cazor--Bonnet Adrian | Chesnier Quentin  | Juery Clément  | Ladureau Baptiste  |
| Développeur          | Product Owner     | Développeur    | Scrum Master      |

| Itération 1 (du 16/02/25 au 02/03/25) ||||
| -------------------- | ----------------- | -------------- | ------------------ |
| Cazor--Bonnet Adrian | Chesnier Quentin  | Juery Clément  | Ladureau Baptiste  |
|  Développeur         |  Scrum Master     | Développeur    | Product Owner      |

| Itération 2 (du 03/03/25 au 17/03/25) ||||
| -------------------- | ----------------- | -------------- | ------------------ |
| Cazor--Bonnet Adrian | Chesnier Quentin  | Juery Clément  | Ladureau Baptiste  |
| Scrum Master         | Développeur       | Product Owner  | Développeur        |

| Itération 3 (du 17/03/25 au 31/03/25) ||||
| -------------------- | ----------------- | -------------- | ------------------ |
| Cazor--Bonnet Adrian | Chesnier Quentin  | Juery Clément  | Ladureau Baptiste  |
| Product Owner        | Développeur       | Scrum Master   | Développeur        |

## Liens utiles
- [Lien vers le drive de la SAÉ](https://drive.google.com/drive/u/2/folders/0AJRCiFHKGX3QUk9PVA)
- [Lien vers le board de la SAÉ](https://github.com/orgs/Rodez-IUT/projects/103)


### Pour lancer l'application sur docker

```
$ docker compose up -d 
$ docker compose exec doliplus composer update
```

Ouvrez votre navigateur préféré et entrer le lien ci-dessous : 

`http://localhost:8080/doliplus/`

Url de connexion à l'API de Dolibarr :
`http://dolibarr.iut-rodez.fr/G2024-41-SAE`

### Pour lancer les tests et PHPStan

Ouvrir un terminal bash dans le container doliplus
```
docker compose exec doliplus bash
```

Lancer PHPStan
```
php ./doliplus/lib/vendor/bin/phpstan --xdebug analyse -c ./phpstan.neon
```


Lancer les tests
```
php ./doliplus/lib/vendor/bin/phpunit
```

Faire un test de couverture de code :
```
php -d xdebug.mode=coverage ./doliplus/lib/vendor/bin/phpunit  --coverage-html='reports/coverage'
```