<?php 

require_once 'lib/twig.php';
require_once 'app/controllers/IndexController.php';
require_once 'app/controllers/ArticlesController.php';
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

    default:
        $controller = new NotFoundController();
        $title = "Erreur 404";
        break;
}

echo $twig->render("Header.twig", ["path" => $path, "title" => $title]);
$controller->index();
echo $twig->render("Footer.twig");