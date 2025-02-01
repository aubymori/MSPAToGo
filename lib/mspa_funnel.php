<?php

$url = "http://cdn.mspaintadventures.com/" . substr($_SERVER["REQUEST_URI"], 6);
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

foreach (explode("\n", $headers) as $header)
{
    header($header);
}

echo $body;

// Prevent Twig from outputting
die();