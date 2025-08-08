<?php
require_once "lib/config.php";

function get_content_type(string $file)
{
    $split = explode(".", $file);
    $ext = $split[array_key_last($split)];

    return match ($ext)
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
}

function mspa_funnel(string $uri, bool $nocdn = false)
{
    $INTERNAL_URI_MAP = [
        "act7.webm"    => "mspa_local/ACT7.webm",
        "collide.webm" => "mspa_local/collide.webm" 
    ];
    
    $EXTERNAL_URL_MAP = [
        "cascade_loader.swf"   => "https://uploads.ungrounded.net/582000/582345_cascade_loaderExt.swf",
        "cascade_segment1.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment1.swf",
        "cascade_segment2.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment2.swf",
        "cascade_segment3.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment3.swf",
        "cascade_segment4.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment4.swf",
        "cascade_segment5.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment5.swf",
    ];

    $file = @$INTERNAL_URI_MAP[strtolower($uri)] ?? null;
    if (!is_null($file))
    {
        $content = @file_get_contents($file);
        if (false === $content)
        {
            http_response_code(404);
            return;
        }
        $content_type = get_content_type($file);
        header("Content-Type: $content_type");
        echo $content;
        die();
    }

    $url = null;
    $external = false;
    if (!Config::isOfflineMode())
    {
        $url = @$EXTERNAL_URL_MAP[strtolower($uri)] ?? null;
        $external = !is_null($url);
    }

    if ($uri == "cascade.swf")
    {
        header("Content-Type: application/x-shockwave-flash");
        echo file_get_contents("static/cascade.swf");
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
        
        $content_type = get_content_type($uri);
        header("Content-Type: $content_type");

        echo $content;
        die();
    }

    if (is_null($url))
        $url = ($nocdn ? "http://www.mspaintadventures.com/" : "http://cdn.mspaintadventures.com/") . $uri;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_HEADER => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_USERAGENT => $_SERVER["HTTP_USER_AGENT"] . " MSPAToGo/1.0"
    ]);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    // Try to get off main server if CDN fails
    $status = intval(substr($headers, 9, 3));
    if (!$external && !$nocdn && $status != 200)
        return mspa_funnel($uri, true);

    foreach (explode("\n", $headers) as $header)
    {
        header($header);
    }

    echo $body;

    // Prevent Twig from outputting
    die();
}