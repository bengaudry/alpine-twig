<?php

require_once 'lib/twig.php';
require_once 'lib/SessionManager.php';

class RegisterController {
    public function index() {
        global $twig;

        $session = SessionManager::getInstance();

        // Rediriger vers la page de profil si l'utilisateur est connecté
        if ($session->isSignedIn()) {
            header('Location: /profile');
            exit;
        }

        // Gestion de la connexion de l'utilisateur
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['name'])
            && isset($_POST['email'])
            && isset($_POST['password'])
        ) {
            Auth::register($_POST['name'], $_POST['email'], $_POST['password']);
            exit;
        }
        
        echo $twig->render(
            "register.twig", 
            ['error' => $_GET['error'] ?? null]
        );
    }
}
