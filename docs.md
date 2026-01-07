# Documentation Technique - Alpine-Twig Blog

## Table des matières
1. [Vue d'ensemble](#vue-densemble)
2. [Architecture MVC](#architecture-mvc)
3. [Design Patterns utilisés](#design-patterns-utilisés)
4. [Structure du projet](#structure-du-projet)
5. [Flux de requêtes](#flux-de-requêtes)
6. [Modules principaux](#modules-principaux)

---

## Vue d'ensemble

Ce projet est une application web de blog développée en PHP suivant le pattern architectural **MVC (Model-View-Controller)**. L'application intègre plusieurs Design Patterns pour assurer une architecture robuste, maintenable et évolutive.

### Technologies utilisées
- **PHP** : Langage backend
- **Twig 3.14** : Moteur de templates
- **MySQL** : Base de données relationnelle
- **PDO** : Couche d'abstraction base de données
- **Markdown** : Support des articles en Markdown (via league/commonmark)
- **Bootstrap** : Framework CSS

---

## Architecture MVC

L'application suit strictement le pattern **MVC** pour séparer les responsabilités et faciliter la maintenance.

### Model (Modèle)
**Localisation :** `app/models/`

Les modèles encapsulent la logique métier et les interactions avec la base de données. Ils sont responsables de :
- La récupération des données
- La validation métier
- Les opérations CRUD
- La logique d'authentification et d'autorisation

**Modèles principaux :**

- **`Articles.php`** : Gestion des articles (CRUD, publication, archivage)
- **`Users.php`** : Gestion des utilisateurs (liste, activation, suppression)
- **`Auth.php`** : Authentification et inscription des utilisateurs
- **`Roles.php`** : Gestion des rôles et permissions
- **`Permissions.php`** : Vérification des droits d'accès


### View (Vue)
**Localisation :** `app/views/` et `app/components/`

Les vues utilisent le moteur de templates **Twig** pour générer le HTML. Cette couche est purement présentationnelle et ne contient aucune logique métier.

**Structure des vues :**
- **Pages complètes** (`app/views/`) : `index.twig`, `article.twig`, `dashboard.twig`, etc.
- **Composants réutilisables** (`app/components/`) : `Head.twig`, `Navbar.twig`, `Footer.twig`

**Avantages de Twig :**
- Syntaxe claire et sécurisée (échappement automatique)
- Héritage de templates
- Support Markdown pour le contenu des articles
- Cache des templates compilés (`cache/`)

### Controller (Contrôleur)
**Localisation :** `app/controllers/`

Les contrôleurs orchestrent les interactions entre les modèles et les vues. Ils :
- Reçoivent les requêtes HTTP
- Appellent les modèles appropriés
- Transmettent les données aux vues
- Gèrent les redirections

**Contrôleurs principaux :**
- **`IndexController.php`** : Page d'accueil avec liste des articles
- **`ArticlesController.php`** : Affichage d'un article spécifique
- **`DashboardController.php`** : Interface d'administration
- **`RegisterController.php`** : Inscription utilisateur
- **`SigninController.php`** : Connexion utilisateur
- **`ProfileController.php`** : Profil utilisateur
- **`NotFoundController.php`** : Gestion des erreurs 404


---

## Design Patterns utilisés

### 1. Pattern Singleton

**Implémentation dans :** `Database.php`, `SessionManager.php`, `Logger.php`

Le pattern **Singleton** garantit qu'une classe n'a qu'une seule instance dans toute l'application et fournit un point d'accès global à cette instance.

**Cas d'usage :**
- **`Database`** : Une seule connexion PDO partagée
- **`SessionManager`** : Gestion centralisée des sessions
- **`Logger`** : Un seul gestionnaire de logs


### 2. Role-Based Access Control

**Implémentation dans :** `Permissions.php`, `Roles.php`

Gestion des permissions basée sur les rôles utilisateurs.


## Structure du projet

```
alpine-twig/
│
├── index.php                    # Front Controller (point d'entrée)
├── composer.json                # Dépendances PHP
│
├── app/
│   ├── controllers/            # Contrôleurs MVC
│   │   ├── IndexController.php
│   │   ├── ArticlesController.php
│   │   ├── DashboardController.php
│   │   └── ...
│   │
│   ├── models/                 # Modèles métier
│   │   ├── Articles.php
│   │   ├── Users.php
│   │   ├── Auth.php
│   │   ├── Permissions.php
│   │   └── Roles.php
│   │
│   ├── views/                  # Templates Twig (pages)
│   │   ├── index.twig
│   │   ├── article.twig
│   │   ├── dashboard.twig
│   │   └── ...
│   │
│   ├── components/             # Composants Twig réutilisables
│   │   ├── Head.twig
│   │   ├── Navbar.twig
│   │   └── Footer.twig
│   │
│   ├── css/                    # Styles Bootstrap et personnalisés
│   └── js/                     # Scripts JavaScript
│
├── db/
│   ├── Database.php            # Singleton de connexion PDO
│   └── init.sql                # Script d'initialisation DB
│
├── lib/
│   ├── twig.php                # Configuration Twig
│   ├── SessionManager.php      # Singleton de gestion session
│   └── Logger.php              # Singleton de logging
│
├── cache/                      # Cache des templates Twig compilés
├── res/                        # Ressources statiques
└── vendor/                     # Dépendances Composer
```

---

## Flux de requêtes

```
1. Client (Navigateur)
   ↓
2. index.php (Front Controller)
   ↓
3. Routing (switch sur REQUEST_URI)
   ↓
4. Instanciation du Contrôleur
   ↓
5. Contrôleur appelle le Modèle
   ↓
6. Modèle interroge la DB (via Database Singleton)
   ↓
7. Modèle retourne les données au Contrôleur
   ↓
8. Contrôleur passe les données à Twig
   ↓
9. Twig compile et render le template
   ↓
10. HTML envoyé au Client
```

---

## Modules principaux

### 1. Système d'authentification (Auth.php)

**Fonctionnalités :**
- Inscription avec validation (email, longueur mot de passe)
- Connexion avec vérification de hash
- Gestion des comptes désactivés
- Logging des événements de sécurité

**Sécurité :**
- Hachage des mots de passe avec `password_hash()`
- Protection contre les injections SQL (requêtes préparées)
- Validation stricte des entrées

### 2. Gestion des sessions (SessionManager.php)

**Singleton** qui encapsule les opérations sur `$_SESSION`.

**Méthodes principales :**
- `set($key, $value)` : Définir une variable de session
- `get($key)` : Récupérer une valeur
- `isSignedIn()` : Vérifier l'authentification
- `destroy()` : Déconnecter l'utilisateur

### 3. Base de données (Database.php)

**Singleton PDO** avec configuration centralisée.

### 4. Logging (Logger.php)

**Singleton** qui enregistre les événements dans `app.log`.

**Usage :**
```php
$logger = Logger::getInstance();
$logger->log("Connexion réussie pour {$username}");
```

### 5. Système de permissions (Permissions.php, Roles.php)

Implémentation d'un Role-Based Access Control :
- Vérification des droits avant chaque action sensible
- Association utilisateurs ↔ rôles (table pivot `role_user`)
- Granularité fine des permissions

**Exemple de vérifications :**
```php
Permissions::canDeleteArticle($userId)
Permissions::canPublishArticle($userId)
Permissions::canManageUsers($userId)
```
