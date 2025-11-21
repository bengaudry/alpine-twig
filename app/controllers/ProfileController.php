<?php

require_once 'lib/twig.php';
require_once 'lib/SessionManager.php';

class ProfileController {

    public function index() {
        global $twig;

        $session = SessionManager::getInstance();

        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!$session->isSignedIn()) {
            header('Location: /signin');
            exit;
        }

        // Gestion de la déconnexion de l'utilisateur
        if (isset($_GET['signout'])) {
            $session->destroy();
            header("Location: /");
            exit;
        }

        echo $twig->render(
            "profile.twig",
            [
                "username" => $session->get("username"),
                "email" => $session->get("email"),
                "isadmin" => $session->isAdmin()
            ]
        );
    }

}
