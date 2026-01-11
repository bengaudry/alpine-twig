<?php 

require_once 'lib/twig.php';
require_once 'lib/Logger.php';
require_once 'lib/SessionManager.php';

require_once 'app/controllers/IndexController.php';
require_once 'app/controllers/ArticlesController.php';
require_once 'app/controllers/ArticleEditorController.php';
require_once 'app/controllers/RegisterController.php';
require_once 'app/controllers/SigninController.php';
require_once 'app/controllers/ProfileController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/NotFoundController.php';

$path = strtok($_SERVER['REQUEST_URI'], '?');

Logger::getInstance()->log("[{$_SERVER['REQUEST_METHOD']}] {$_SERVER['REQUEST_URI']}");

$controller; $title;
switch ($path) {
    case '/':
        $controller = new IndexController();
        $title = "Accueil";
        break;
        
    case '/article':
        $controller = new ArticlesController();
        $title = "Article";
        break;

    case '/register':
        $controller = new RegisterController();
        $title = "Inscription";
        break;

    case '/signin':
        $controller = new SigninController();
        $title = "Inscription";
        break;

    case '/profile':
        $controller = new ProfileController();
        $title = "Profil";
        break;

    case '/dashboard':
        $controller = new DashboardController();
        $title = "Tableau de bord";
        break;

    case '/edit-article':
        $controller = new ArticleEditorController();
        $title = "Édition d'article";
        break;

    default:
        $controller = new NotFoundController();
        $title = "Erreur 404";
        break;
}

$session = SessionManager::getInstance();

// Output buffering pour éviter les problèmes d'en-têtes déjà envoyés
ob_start();

error_log(Permissions::canCreateArticle($session->get('user_id')) ? "Can create articles" : "Cannot create articles");
echo $twig->render("Head.twig", [
    "title" => $title,
]);
echo $twig->render(
    "Navbar.twig", 
    [
        "path" => $path, 
        "isSignedIn" => $session->isSignedIn(),
        "canCreateArticles" => Permissions::canCreateArticle($session->get('user_id'))
    ]
);
$controller->index();
echo $twig->render("Footer.twig");

ob_end_flush();
