<?php
require "vendor/autoload.php";

function http_get(string $url, string &$body): int
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_HEADER => 1,
        CURLOPT_RETURNTRANSFER => 1
    ]);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    return intval(substr($headers, 9, 3));
}

$twigLoader = new \Twig\Loader\FilesystemLoader("templates");
$twig = new \Twig\Environment($twigLoader, []);

$data = (object)[];
$twig->addGlobal("data", $data);

require "router.php";