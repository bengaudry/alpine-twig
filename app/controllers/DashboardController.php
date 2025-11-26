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

        $data = $this->fetchData($_GET['view']);
        error_log(json_encode($data));

        echo $twig->render(
            "dashboard.twig",
            [
                "view" => $_GET['view'],
                "pages" => DashboardController::$dashboardPages,
                "username" => $session->get("username"),
                "email" => $session->get("email"),
                "data" => $data
            ]
        );
    }

    private function fetchData(string $view) {
        switch ($view) {
            case 'users':
                return ['users' => Users::getAll()];

            case 'articles':
                return Articles::getArticles();
                
            default:
                return [];
        }
    }
}
