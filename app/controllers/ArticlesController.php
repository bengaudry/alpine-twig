<?php 

require_once "app/models/articles.php";
require_once "lib/twig.php";

class ArticlesController {

    public function index() {
        global $twig;

        if (!isset($_GET["slug"])) {
            return $this->redirectError();
        }

        $article = Articles::getArticle($_GET["slug"]);

        if ($article == null) $this->redirectError();

        echo $twig->render(
            "article.twig",
            ["article" => $article]
        );
    }
    
    private function redirectError() {
        header("Location: /404");
        exit;
    }
}
