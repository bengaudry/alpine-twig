<?php 

require_once "app/models/Articles.php";
require_once "app/models/Comments.php";
require_once "lib/twig.php";
require_once "app/models/Tags.php";
require_once 'db/Database.php';

class ArticlesController {

    public function index(): void {
        global $twig;

        if (!isset($_GET["slug"])) {
            $this->redirectError();
            return;
        }

        $article = Articles::getArticle($_GET["slug"]);

        if ($article == null) {
            $this->redirectError();
            return;
        }

        echo $twig->render(
            "article.twig",
            [
                "article"  => $article,
                "comments" => Comments::getByArticleId($article['id']),
                "tags"     => Tags::getArticleTags()[$article['id']] ?? []
            ]
        );
    }
    
    private function redirectError() {
        header("Location: /404");
        exit;
    }
}
