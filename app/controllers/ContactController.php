<?php 

require_once "app/models/articles.php";
require_once "lib/twig.php";

class ContactController {
    private $articlesModel;

    public function __construct() {
        $this->articlesModel = new Articles();
    }

    public function index() {
        global $twig;
        
        echo $twig->render("contact.twig");
    }
}
