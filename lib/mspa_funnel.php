<?php

function mspa_funnel(string $uri, bool $nocdn = false)
{
    $url = match ($uri) {
        "cascade.swf" => "https://www.homestuck.com/flash/hs2/cascade/cascade.swf",
        default => ($nocdn ? "http://www.mspaintadventures.com/" : "http://cdn.mspaintadventures.com/") . $uri
    };
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_HEADER => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_USERAGENT => $_SERVER["HTTP_USER_AGENT"]
    ]);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    // Try to get off main server if CDN fails
    $status = intval(substr($headers, 9, 3));
    if (!$nocdn && $status != 200)
        return mspa_funnel($uri, true);

    foreach (explode("\n", $headers) as $header)
    {
        header($header);
    }

    echo $body;

    // Prevent Twig from outputting
    die();
}