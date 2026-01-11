<?php 

require_once "app/models/Articles.php";
require_once "app/models/Comments.php";
require_once "lib/twig.php";
require_once "app/models/Tags.php";
require_once 'db/Database.php';

class ArticleEditorController {

    public function index(): void {
        global $twig;

        if (!Permissions::canEditAllArticles(SessionManager::getInstance()->get('user_id'))) {
            $this->redirectError();
            return;
        }

        $id = $_GET["id"] ?? null;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titre = $_POST["titre"];
            $contenu = $_POST["contenu"];
            $statut = $_POST["statut"];
            $tags = $_POST["tags"];

            error_log("TAGS:");
            error_log(print_r($tags, true));

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titre)));

            if (isset($_GET["id"])) {
                Logger::getInstance()->log("Modification d'un article existant par l'utilisateur " . SessionManager::getInstance()->get('user_id'));
                $id = intval(value: $_GET["id"]);
                if (!empty($titre) && !empty($contenu)) {
                    Articles::updateArticle($id, $titre, $slug, $contenu, $statut);
                    Articles::synchroTags($id, $tags);
                    $article = Articles::getArticle($id);
                }
            } else {
                Logger::getInstance()->log("Création d'un nouvel article par l'utilisateur " . SessionManager::getInstance()->get('user_id'));
                $id = Articles::createArticle($titre, $slug, $contenu, $statut);
            }
        }

        $article = $id ? Articles::getArticleFromId($id) : null;

        if ($id != null && $article == null) {
            $this->redirectError();
            return;
        }

        $curTags = $id ? Tags::find($id) : [];
        error_log(print_r($curTags, true));
        $curTagsId = array_map(fn($t) => $t["id"], $curTags);
        echo $twig->render(
            "edit_article.twig",
            [
                "isCreation" => $id == null,
                "article"  => $article,
                "allTags"     => Tags::findAll(),
                "curTagsId" => $curTagsId
            ]
        );
    }
    
    private function redirectError() {
        header("Location: /404");
        exit;
    }
}
