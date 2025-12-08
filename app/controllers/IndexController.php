<?php 

require_once "app/models/Articles.php";
require_once "lib/twig.php";

class IndexController {
    public function index() {
        global $twig;
        
        $articles = Articles::getPublishedArticles();
        echo $twig->render("index.twig", $articles);
    }
}
