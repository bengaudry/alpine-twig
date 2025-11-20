<?php

require_once 'lib/twig.php';
require_once 'lib/SessionManager.php';

class ProfileController {

    public function index() {
        global $twig;

        if (isset($_POST['signout'])) {
            $session = SessionManager::getInstance();
            $session->destroy();
            header("Location: /");
            exit;
        }

        echo $twig->render("profile.twig");
    }

}
