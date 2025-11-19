<?php

require_once 'lib/twig.php';

class SigninController {
    public function index() {
        global $twig;
        
        echo $twig->render("signin.twig");
    }
}
