<?php

require_once 'lib/twig.php';

class RegisterController {
    public function index() {
        global $twig;
        
        echo $twig->render("register.twig");
    }
}
