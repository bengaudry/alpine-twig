<?php

require_once 'lib/twig.php';
require_once 'app/models/auth.php';


class SigninController {
    public function index() {
        global $twig;

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['email'])
            && isset($_POST['password'])
        ) {
            Auth::signIn($_POST['email'], $_POST['password']);
            exit;
        }
        
        echo $twig->render(
            "signin.twig", 
            ['error' => $_GET['error'] ?? null]
        );
    }
}
