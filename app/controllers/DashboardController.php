<?php

require_once 'lib/twig.php';
require_once 'lib/SessionManager.php';

require_once 'app/models/Users.php';
require_once 'app/models/Articles.php';
require_once 'app/models/Comments.php';
require_once 'app/models/Tags.php';

class DashboardController {
    public function index() {
        global $twig;
        
        $this->redirectIfUnauthorized();
        
        $session = SessionManager::getInstance();

        $view = $_GET['view'];

        // Gestion des actions POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $view = $this->handlePostActions($session);
        }

        echo $twig->render(
            "dashboard.twig",
            [
                "view" => $view,
                "pages" => self::getDashboardPages(),
                "username" => $session->get("username"),
                "email" => $session->get("email"),
                "data" => $this->fetchData($view)
            ]
        );
    }

    private function getDashboardPages() {
        $session = SessionManager::getInstance();
        $user_id = $session->get("user_id");

        // pages accessibles par défaut (pour les modérateurs)
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

        if (Permissions::canManageTags($user_id)) {
            $dashboardPages['tags'] = 'Gestion des tags';
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
            case 'stats':
                return [
                    'totalUsers' => json_encode(Users::countAll()),
                    'publishedArticles' => Articles::countPublishedArticles(),
                    'awaitingComments' => Comments::countPendingComments()
                ];

            case 'users':
                return ['users' => Users::getAll(), 'roles' => Roles::getAll()];

            case 'articles':
                return [
                    'articles' => Articles::getAllArticles(),
                    'canDeleteArticles' => Permissions::canDeleteArticle(SessionManager::getInstance()->get('user_id'))
                ];

            case 'comments':
                return ['comments' => Comments::getAll()];

            case 'tags':
                return ['tags' => Tags::findAll()];

            default:
                return [];
        }
    }


    private function handlePostActions(SessionManager $session): string {
        // Gestion des articles
        if (isset($_POST["articles:delete"]) && Permissions::canDeleteArticle($session->get('user_id'))) {
            Articles::deleteArticle($_POST['articles:delete']);
            return "articles";
        }
        if (isset($_POST["articles:archive"]) && Permissions::canEditAllArticles($session->get('user_id'))) {
            Articles::archiveArticle($_POST['articles:archive']);
            return "articles";
        }
        if (isset($_POST["articles:publish"]) && Permissions::canPublishArticle($session->get('user_id'))) {
            Articles::publishArticle($_POST['articles:publish']);
            return "articles";
        }

        // Gestion des utilisateurs
        if (isset($_POST["users:toggle-activation"]) && Permissions::canManageUsers($session->get('user_id'))) {
            Users::toggleActivation($_POST['users:toggle-activation']);
            return "users";
        }
        if (isset($_POST["users:toggle-role_roleid"]) && Permissions::canManageUsers($session->get('user_id'))) {
            Roles::toggleUserRole(
                $_POST['users:user-id'],
                $_POST['users:toggle-role_roleid']
            );
            return "users";
        }
        if (isset($_POST["users:delete-user"]) && Permissions::canManageUsers($session->get('user_id'))) {
            Users::delete($_POST['users:delete-user']);
            return "users";
        }

        // Gestion des commentaires
        if (isset($_POST["comments:approve"]) && Permissions::canManageComments($session->get('user_id'))) {
            Comments::approveComment($_POST['comments:approve']);
            return "comments";
        }
        if (isset($_POST["comments:reject"]) && Permissions::canManageComments($session->get('user_id'))) {
            Comments::rejectComment($_POST['comments:reject']);
            return "comments";
        }
        if (isset($_POST["comments:delete"]) && Permissions::canManageComments($session->get('user_id'))) {
            Comments::deleteComment($_POST['comments:delete']);
            return "comments";
        }


        // Gestion des tags
        if (isset($_POST["tags:create"]) && Permissions::canManageTags($session->get('user_id'))) {
            error_log("Creating tag: " . $_POST['new_tag_name']);
            Tags::create($_POST['new_tag_name']);
            return "tags";
        }
        if (isset($_POST["tags:delete"]) && Permissions::canManageTags($session->get('user_id'))) {
            Tags::delete((int)$_POST['tags:delete']);
            return "tags";
        }
        return $_GET['view'];
    }
}
