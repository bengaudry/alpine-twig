<?php

require_once 'lib/twig.php';
require_once 'lib/SessionManager.php';

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

        echo $twig->render(
            "dashboard.twig",
            [
                "view" => $_GET['view'],
                "pages" => DashboardController::$dashboardPages,
                "username" => $session->get("username"),
                "email" => $session->get("email")
            ]
        );
    }
}
