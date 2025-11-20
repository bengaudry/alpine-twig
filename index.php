<?php 

require_once 'lib/twig.php';
require_once 'lib/SessionManager.php';

require_once 'app/controllers/IndexController.php';
require_once 'app/controllers/ArticlesController.php';
require_once 'app/controllers/RegisterController.php';
require_once 'app/controllers/SigninController.php';
require_once 'app/controllers/ProfileController.php';
require_once 'app/controllers/NotFoundController.php';

$path = strtok($_SERVER['REQUEST_URI'], '?');

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

    default:
        $controller = new NotFoundController();
        $title = "Erreur 404";
        break;
}

$session = SessionManager::getInstance();

echo $twig->render("Head.twig", ["title" => $title]);
error_log($session->isSignedIn() ? "signed in" : "not signed in");
echo $twig->render(
    "Navbar.twig", 
    ["path" => $path, "isSignedIn" => $session->isSignedIn()]
);
$controller->index();
echo $twig->render("Footer.twig");
