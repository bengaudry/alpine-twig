<?php

require_once 'lib/twig.php';
require_once 'lib/SessionManager.php';

require_once 'app/models/Users.php';
require_once 'app/models/Articles.php';

class DashboardController {
    public function index() {
        global $twig;
        
        $this->redirectIfUnauthorized();
        
        $session = SessionManager::getInstance();
        $view = $_GET['view'];

        // Gestion des actions POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["articles:delete"]) && Permissions::canDeleteArticle($session->get('user_id'))) {
                Articles::deleteArticle($_POST['articles:delete']);
                $view = "articles";
            }
            if (isset($_POST["articles:archive"]) && Permissions::canEditAllArticles($session->get('user_id'))) {
                Articles::archiveArticle($_POST['articles:archive']);
                $view = "articles";
            }
            if (isset($_POST["articles:publish"]) && Permissions::canPublishArticle($session->get('user_id'))) {
                Articles::publishArticle($_POST['articles:publish']);
                $view = "articles";
            }

            if (isset($_POST["users:toggle-activation"]) && Permissions::canManageUsers($session->get('user_id'))) {
                Users::toggleActivation($_POST['users:toggle-activation']);
                $view = "users";
            }
            if (isset($_POST["users:toggle-role_roleid"]) && Permissions::canManageUsers($session->get('user_id'))) {
                Roles::toggleUserRole(
                    $_POST['users:user-id'],
                    $_POST['users:toggle-role_roleid']
                );
                $view = "users";
            }
            if (isset($_POST["users:delete-user"]) && Permissions::canManageUsers($session->get('user_id'))) {
                Users::delete($_POST['users:delete-user']);
                $view = "users";
            }
        }

        $data = $this->fetchData($view);

        echo $twig->render(
            "dashboard.twig",
            [
                "view" => $view,
                "pages" => self::getDashboardPages(),
                "username" => $session->get("username"),
                "email" => $session->get("email"),
                "data" => $data
            ]
        );
    }

    private function getDashboardPages() {
        $session = SessionManager::getInstance();
        $user_id = $session->get("user_id");

        $dashboardPages = [
            'stats' => 'Statistiques',
            'articles' => 'Gestion des articles',
        ];

        // Ajout des pages en fonction des permissions
        if (Permissions::canManageUsers($user_id)) {
            $dashboardPages['users'] = 'Gestion des utilisateurs';
        }

        if (Permissions::canManageComments($user_id)) {
            $dashboardPages['comments'] = 'Gestion des commentaires';
        }

        if (!Permissions::hasAdminAccess($session->get('user_id'))) {
            header('Location: /profile');
            exit;
        }

        return $dashboardPages;
    }

    private function redirectIfUnauthorized() {
        $session = SessionManager::getInstance();
        
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!$session->isSignedIn()) {
            header('Location: /signin');
            exit;
        }

        // Rediriger vers le profil si l'utilisateur n'est pas administrateur
        if (!Permissions::hasAdminAccess($session->get('user_id'))) {
            header('Location: /profile');
            exit;
        }

        // Rediriger vers la page de statistiques par défaut
        if (
            !isset($_GET['view'])
            || !array_key_exists($_GET['view'], self::getDashboardPages())
        ) {
            header('Location: /dashboard?view=stats');
            exit;
        }
    }

    private function fetchData(string $view) {
        switch ($view) {
            case 'users':
                return ['users' => Users::getAll(), 'roles' => Roles::getAll()];

            case 'articles':
                return [
                    'articles' => Articles::getAllArticles(),
                    'canDeleteArticles' => Permissions::canDeleteArticle(SessionManager::getInstance()->get('user_id'))
                ];
                
            default:
                return [];
        }
    }
}
