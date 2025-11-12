<?php

require_once "vendor/autoload.php";

$twig_loader = new Twig\Loader\FilesystemLoader(["app/views", "app/components"]);

$options_prod = ['cache' => 'cache', 'autoescape' => true];
$options_dev = ['cache' => false, 'autoescape' => true];

$twig = new Twig\Environment($twig_loader);
