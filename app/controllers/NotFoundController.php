<?php 

require_once "lib/twig.php";

class NotFoundController {
    public function index() {
        global $twig;        
        echo $twig->render("404.twig");
    }
}
