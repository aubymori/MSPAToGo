<?php
require_once "lib/config.php";

function mspa_funnel(string $uri, bool $nocdn = false)
{
    if ($uri == "cascade.swf")
    {
        header("Content-Type: application/x-shockwave-flash");
        echo file_get_contents("static/cascade.swf");
        die();
    }
    else if ($uri == "ACT7.webm")
    {
        header("Content-Type: video/webm");
        $content = @file_get_contents("mspa_local/ACT7.webm");
        if ($content === false)
        {
            http_response_code(404);
            return;
        }
        echo $content;
        die();
    }
    else if ($uri == "collide.webm")
    {
        header("Content-Type: video/webm");
        $content = @file_get_contents("mspa_local/collide.webm");
        if ($content === false)
        {
            http_response_code(404);
            return;
        }
        echo $content;
        die();
    }

    if (Config::isOfflineMode())
    {
        $content = @file_get_contents("mspa_local/" . $uri);
        if ($content === false)
        {
            http_response_code(404);
            return;
        }
        $split = explode(".", $uri);
        $ext = $split[array_key_last($split)];

        $content_type = match ($ext)
        {
            "js" => "text/javascript",
            "css" => "text/css",
            "json" => "application/json",
            "txt" => "text/plain",
            "html" => "text/html",
            "php" => "text/html",
            "xml" => "application/xml",
            "swf" => "application/x-shockwave-flash",
            "flv" => "video/x-flv",
            "png" => "image/png",
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "gif" => "image/gif",
            "bmp" => "image/bmp",
            "svg" => "image/svg+xml",
            "zip" => "application/zip",
            "mp3" => "audio/mpeg",
            "mp4" => "video/mp4",
            default => mime_content_type($ext)
        };

        header("Content-Type: $content_type");

        echo $content;
        die();
    }

    $url = ($nocdn ? "http://www.mspaintadventures.com/" : "http://cdn.mspaintadventures.com/") . $uri;
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