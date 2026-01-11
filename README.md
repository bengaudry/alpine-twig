# Projet PHP + Alpine + Twig

Membres : Ben Gaudry, Daniel Caille, Kevin Bertaux

La documentation technique est disponible dans le fichier `docs.md`


## Installation

1. `git clone https://github.com/bengaudry/alpine-twig`
2. `cd alpine-twig`
3. `composer install`
4. Démarrer le server sql avec xampp
5. Importer le fichier `db/init.sql` dans PHPMyAdmin


## Démarrage

Démarrer le server sql avec xampp

```sh
php -S localhost:8000
```


## Répartition du travail

Nous avons utilisé la gestion de projet sur GitHub pour nous répartir les tâches.
Nous n'avons pas divisé la gestion des tâches par type (controlleur, modèles, vues...), mais par fonctionnalités :

1. Ben Gaudry

- Bases du projet (reprises du projet alpine du début d'année)
- Authentification
- Router
- Dashboard
  - Affichage des statistiques
  - Gestion des articles
  - Gestion des utilisateurs
  - Gestion des commentaires
  - Gestion des tags
  - Gestion des rôles (vérification)
  - Gestion des permissions (vérification)


2. Daniel Caille

- Barre de navigation
- Modification du code de base pour intégrer bootstrap
- Modèle Comments
- Police dyslexique
- Affichage des articles et des commentaires


3. Kevin Bertaux (arrivé après sur le projet)

- Modèle Tags
- Page d'édition d'articles


## Difficultés rencontrées

Nous avons peu rencontré de difficultés. Simplement, la compréhension du sujet et du systèmes de rôles / permissions était difficile au départ.
Quelques difficultés de mise en page avec bootstrap aussi (notamment pour la barre de navigation).
De plus, XAMPP posait parfois problème (impossible d'accéder à PhpMyAdmin sans réinstaller XAMPP).


## Ce que nous avons retiré de ce projet

- Utilisation des Design Pattern : Singleton, MVC
- Découverte de technologies : PHP, Bootstrap, Twig...
- Organisation de code
- Répartition du travail / gestion de projet
- Meilleure compréhension de GIT / Github (branches, pull requests, dépôt distant, merge conflicts ...)
