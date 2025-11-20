<?php

require_once 'lib/twig.php';

class RegisterController {
    public function index() {
        global $twig;

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
