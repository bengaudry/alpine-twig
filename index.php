<?php 

require_once 'lib/twig.php';
require_once 'app/controllers/IndexController.php';
require_once 'app/controllers/ContactController.php';
require_once 'app/controllers/ArticlesController.php';

$path = strtok($_SERVER['REQUEST_URI'], '?');

$controller; $title;
switch ($path) {
    case '/':
        $controller = new IndexController();
        $title = "Accueil";
        break;
        
    case '/contact':
        $controller = new ContactController();
        $title = "Contact";
        break;
        
    case '/article':
        $controller = new ArticlesController();
        $title = "Article";
        break;
}

echo $twig->render("Header.twig", ["path" => $path, "title" => $title]);
$controller->index();
echo $twig->render("Footer.twig");