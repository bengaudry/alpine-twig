# Documentation Technique - Alpine-Twig Blog

## Table des matières
1. [Vue d'ensemble](#vue-densemble)
2. [Architecture MVC](#architecture-mvc)
3. [Design Patterns utilisés](#design-patterns-utilisés)
4. [Flux de requêtes](#flux-de-requêtes)
5. [Autres modules](#autres-modules)

---

## Vue d'ensemble

Ce projet est une application web de blog développée en PHP suivant le pattern MVC. L'application intègre plusieurs Design Patterns pour assurer une architecture robuste, maintenable et évolutive.


### Technologies utilisées
- **PHP** : Langage backend
- **Twig 3.14** : Moteur de templates
- **MySQL** : Base de données
- **PDO** : Connexion à la base de données
- **Markdown** : Support des articles en Markdown (via league/commonmark)
- **Bootstrap** : Pour gérer simplement les styles

---

## Architecture MVC

L'application suit le pattern MVC pour séparer les responsabilités et faciliter la maintenance.

### Modèles
**Dossier :** `app/models/`

Les modèles encapsulent la logique métier et les interactions avec la base de données. Ils sont responsables de :
- La récupération des données
- La validation
- Les opérations CRUD
- La logique d'authentification et d'autorisation

**Modèles principaux :**

- **`Articles.php`** : Gestion des articles (CRUD, publication, archivage)
- **`Users.php`** : Gestion des utilisateurs (liste, activation, suppression)
- **`Auth.php`** : Authentification et inscription des utilisateurs
- **`Roles.php`** : Gestion des rôles et permissions
- **`Permissions.php`** : Vérification des droits d'accès
- **`Tags.php`** : CRUD complet des tags
- **`Comments.php`** : Gestion des commentaires


### Vues
**Dossier :** `app/views/` et `app/components/`

Les vues utilisent le moteur de templates **Twig** pour générer le HTML. Cette couche est purement présentationnelle et ne contient aucune logique métier.

**Structure des vues :**
- **Pages complètes** (`app/views/`) : `index.twig`, `article.twig`, `dashboard.twig`, etc.
- **Composants réutilisables** (`app/components/`) : `Head.twig`, `Navbar.twig`, `Footer.twig` (pour éviter la redondance de code)

**Avantages de Twig :**
- Syntaxe claire et sécurisée (échappement automatique)
- Héritage de templates
- Support Markdown pour le contenu des articles
- Cache des templates compilés (`cache/`)


### Contrôleurs
**Dossier :** `app/controllers/`

Les contrôleurs gèrent les interactions entre les modèles et les vues :
- Reçoivent les requêtes
- Vérifient les permissions
- Appellent les modèles appropriés
- Transmettent les données aux vues
- Gèrent les redirections


Nous avons créé un contrôlleur par page :

- **`IndexController.php`** : Page d'accueil avec liste des articles
- **`ArticlesController.php`** : Affichage d'un article spécifique
- **`DashboardController.php`** : Dashboard administrateur
- **`RegisterController.php`** : Inscription utilisateur
- **`SigninController.php`** : Connexion utilisateur
- **`ProfileController.php`** : Profil utilisateur
- **`NotFoundController.php`** : Gestion des erreurs 404


---

## Design Patterns utilisés

### 1. Pattern Singleton

Implémentation dans `Database.php`, `SessionManager.php`, `Logger.php`

Le pattern Singleton garantit qu'une classe n'a qu'une seule instance dans toute l'application et fournit un point d'accès global à cette instance.

**Cas d'usage :**
- **`Database`** : Une seule connexion PDO partagée
- **`SessionManager`** : Gestion centralisée des sessions
- **`Logger`** : Un seul gestionnaire de logs


### 2. Role-Based Access Control

Implémentation dans `Permissions.php`, `Roles.php`

Gestion des permissions basée sur les rôles utilisateurs.


---

## Flux de requêtes

```
1. Client (Navigateur)
   ↓
2. index.php (Gestion du routing)
   ↓
3. Instanciation du Contrôleur adapté
   ↓
4. Contrôleur appelle les Modèles
   ↓
5. Modèle interroge la DB (via Database Singleton)
   ↓
6. Modèle retourne les données au Contrôleur
   ↓
7. Contrôleur passe les données à Twig
   ↓
8. Twig compile et render le template
   ↓
9. HTML envoyé au Client
```

---

## Autres modules

### 1. lib/SessionManager.php

Singleton qui gère les opérations sur `$_SESSION`.


### 2. db/Database.php

Singleton PDO pour les appels à la BD.


### 3. lib/Logger.php

Singleton qui enregistre les événements dans `app.log`.

**Utilisation :**
```php
$logger = Logger::getInstance();
$logger->log("Connexion réussie pour {$username}");
```

### 4. lib/twig.php

Centralise du code répétitif lié à l'utilisation de twig
