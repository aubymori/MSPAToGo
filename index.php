<?php
require "vendor/autoload.php";

$twigLoader = new \Twig\Loader\FilesystemLoader("templates");
$twig = new \Twig\Environment($twigLoader, []);

require "router.php";