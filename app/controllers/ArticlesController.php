<?php 

require_once "app/models/Articles.php";
require_once "lib/twig.php";

class ArticlesController {

    public function index(): void {
        global $twig;

        if (!isset($_GET["slug"])) {
            $this->redirectError();
            return;
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
