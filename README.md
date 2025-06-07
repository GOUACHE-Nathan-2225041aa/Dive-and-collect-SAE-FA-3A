# 🚀 Mon Projet Symfony WebApp

## 📌 Prérequis

Avant de commencer, assure-toi d'avoir installé :

- **PHP 8.1+** → [Télécharger PHP](https://www.php.net/downloads)
- **Composer** → [Installer Composer](https://getcomposer.org/download/)
- **Symfony CLI** → [Installer Symfony CLI](https://symfony.com/download)

---

## 📥 Installation

1. **Cloner le projet**
   ```sh
   git clone https://github.com/GOUACHE-Nathan-2225041aa/Dive-and-collect-SAE-FA-3A
   cd mon-projet

2. **Installer les dépendances**
   ```sh
   composer install

3. **Configurer l'environnement**
   Copie le fichier .env en .env.local et configure ta base de données :
   ```sh
   cp .env .env.local

4. **Créer la base de données**
   ```sh
   symfony console doctrine:database:create
   ````
   ```sh 
   php bin/console make:migration    
   ````
   ```sh 
   symfony console doctrine:migrations:migrate
   ````
   ```sh 
   symfony console doctrine:fixtures:load
   ````

5. **Lancer le serveur**
   ```sh 
   symfony server:start

6. **compiler**
   ```sh
   php bin/console asset-map:compile

7. **Accéder à l'application**
   Ouvre http://127.0.0.1:8000 dans ton navigateur.

8. **Vérifier son php.ini**
   Pour les fonctionnalités photo il faut vérifier que le "fileinfo" dans le php.ini soit à true.
---

## 📂 Structure du projet

```bash
mon-projet/
│── assets/          # Fichiers CSS/JS pour Webpack Encore
│── config/          # Configuration Symfony (services, routes, etc.)
│── migrations/      # Fichiers de migration de la base de données
│── public/          # Fichiers accessibles publiquement (CSS, JS, images)
│── src/             # Code source de l'application
│── templates/       # Fichiers Twig pour les vues
│── translations/    # Fichiers de traduction
│── var/             # Fichiers temporaires (cache, logs)
│── vendor/          # Dépendances installées via Composer
│── .env             # Configuration des variables d’environnement
│── composer.json    # Liste des dépendances PHP
│── symfony.lock     # Version des dépendances installées
└── README.md        # Documentation du projet
```
---

## 📌 Commandes utiles

- **Créer un controller**
    ```sh 
    symfony console make:controller NomDuController

- **Créer un listener**
    ```sh 
    symfony console make:listener NomDuController

- **Créer une entité (base de données)**
    ```sh 
    symfony console make:entity

- **Exécuter les migrations**
    ```sh 
    symfony console doctrine:migrations:migrate

- **Vider le cache**
    ```sh 
    symfony console cache:clear

- **lancer les tests**
    ```sh
    symfony php bin/phpunit

- **lancer le clear de la BBD avec la mise en place d'un jeux de test**
    ```sh
    symfony console doctrine:fixtures:load
