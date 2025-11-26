<?php 

require_once "app/models/articles.php";
require_once "lib/twig.php";

class IndexController {
    public function index() {
        global $twig;
        
        $articles = Articles::getArticles();
        echo $twig->render("index.twig", $articles);
    }
}
