<?php

require_once 'lib/twig.php';
require_once 'lib/SessionManager.php';

require_once 'app/models/users.php';
require_once 'app/models/articles.php';

class DashboardController {
    private static $dashboardPages = [
        'stats' => 'Statistiques',
        'users' => 'Gestion des utilisateurs',
        'articles' => 'Gestion des articles',
        'comments' => 'Gestion des commentaires'
    ];

    public function index() {
        global $twig;
        
        $this->redirectIfUnauthorized();
        
        $session = SessionManager::getInstance();
        $view = $_GET['view'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["articles:delete"])) {
                Articles::deleteArticle($_POST['articles:delete']);
                $view = "articles";
            }
            if (isset($_POST["articles:archive"])) {
                Articles::archiveArticle($_POST['articles:archive']);
                $view = "articles";
            }
            if (isset($_POST["articles:publish"])) {
                Articles::publishArticle($_POST['articles:publish']);
                $view = "articles";
            }
        }

        $data = $this->fetchData($view);

        echo $twig->render(
            "dashboard.twig",
            [
                "view" => $view,
                "pages" => DashboardController::$dashboardPages,
                "username" => $session->get("username"),
                "email" => $session->get("email"),
                "data" => $data
            ]
        );
    }

    private function redirectIfUnauthorized() {
        $session = SessionManager::getInstance();
        
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!$session->isSignedIn()) {
            header('Location: /signin');
            exit;
        }

        // Rediriger vers le profil si l'utilisateur n'est pas administrateur
        if (!$session->isAdmin()) {
            header('Location: /profile');
            exit;
        }

        // Rediriger vers la page de statistiques par défaut
        if (
            !isset($_GET['view'])
            || !array_key_exists($_GET['view'], DashboardController::$dashboardPages)
        ) {
            header('Location: /dashboard?view=stats');
            exit;
        }
    }

    private function fetchData(string $view) {
        switch ($view) {
            case 'users':
                return ['users' => Users::getAll()];

            case 'articles':
                return Articles::getAllArticles();
                
            default:
                return [];
        }
    }
}
