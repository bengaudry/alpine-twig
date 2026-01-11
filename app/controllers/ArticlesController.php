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
    public function edit(): void {
        global $twig;

        if (!isset($_GET["slug"])) {
            $this->redirectError();
            return;
        }
        $id = (int)$_GET["id"];
        $pdo = Database::getInstance()->getConnection();
        $article = Articles::getArticle($id);
        if (!$article) {
            $this->redirectError();
            return;
        }
        $error = null;
        $success = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titre = $_POST["titre"];
            $contenu = $_POST["contenu"];
            $statut = $_POST["statut"];
            $tags = $_POST["tags"];
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titre)));

            if (!empty($titre) && !empty($contenu)) {
                Articles::updateArticle($id, $titre, $slug, $contenu, $statut);
                Articles::synchroTags($id, $tags);
                $success = "Article updated";
                $article = Articles::getArticle($id);
            } else {
                $error = "fill all the fields";
            }
        }
        $allTags = Tags::findAll();
        $curTags = Tags::find($id);
        $curTagsId = array_map(fn($t) => $t["id"], $curTags);
         echo $twig->render(
             "articleEdit.twig",
             [
                 "article"  => $article,
                 "error"    => $error,
                 "success"  => $success,
                 "tags"     => $allTags,
                 "curTagsId" => $curTagsId
             ]
         );
    }
}
