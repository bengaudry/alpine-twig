<?php 

require_once "app/models/articles.php";
require_once "lib/twig.php";

class ArticlesController {
    private $articlesModel;

    public function __construct() {
        $this->articlesModel = new Articles();
    }

    public function index() {
        global $twig;

        if (isset($_GET["slug"])) {
            $article =  $this->articlesModel->getArticle($_GET["slug"]);
            echo $twig->render(
                "article.twig",
                ["article" => $article]
            );
        } else {
            echo "Not found";
        }
    }
}
