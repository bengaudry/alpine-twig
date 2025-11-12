<?php 

require_once "app/models/articles.php";
require_once "lib/twig.php";

class IndexController {
    private $articlesModel;

    public function __construct() {
        $this->articlesModel = new Articles();
    }

    public function index() {
        global $twig;
        
        $articles = $this->articlesModel->getArticles();
        echo $twig->render("index.twig", $articles);
    }
}
