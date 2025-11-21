<?php

require_once "vendor/autoload.php";

use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

$twig_loader = new Twig\Loader\FilesystemLoader(["app/views", "app/components"]);

$options_prod = ['cache' => 'cache', 'autoescape' => true];
$options_dev = ['cache' => false, 'autoescape' => true];

$twig = new Twig\Environment($twig_loader);
$twig->addExtension(new MarkdownExtension());
$twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
    public function load($class) {
        if (MarkdownRuntime::class === $class) {
            return new MarkdownRuntime(new DefaultMarkdown());
        }
    }
});
