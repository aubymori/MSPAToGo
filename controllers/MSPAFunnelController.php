<?php
namespace MSPAToGo\Controller;

use MSPAToGo\RequestMetadata;
use MSPAToGo\Network;
use MSPAToGo\ServerConfig;

class MSPAFunnelController
{
    /**
     * Headers received from MSPA that should NOT be reflected
     * in our response.
     */
    private static array $illegalResponseHeaders = [
        "transfer-encoding",
    ];

    private static array $internalUriMap = [
        "act7.webm"    => "mspa_local/ACT7.webm",
        "collide.webm" => "mspa_local/collide.webm" 
    ];

    private static array $externalUrlMap = [
        "cascade_loader.swf"   => "https://uploads.ungrounded.net/582000/582345_cascade_loaderExt.swf",
        "cascade_segment1.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment1.swf",
        "cascade_segment2.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment2.swf",
        "cascade_segment3.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment3.swf",
        "cascade_segment4.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment4.swf",
        "cascade_segment5.swf" => "https://uploads.ungrounded.net/userassets/3591000/3591093/cascade_segment5.swf",
    ];

    public function get(RequestMetadata $request): void
    {
        $uri = $_SERVER["REQUEST_URI"];
        // Regular case
        if (strtolower($request->path[0]) == "mspa")
        {
            $uri = substr($uri, 6);
        }
        // Weird relative URL Openbound case
        else if (strtolower($request->path[0]) == "read")
        {
            $uri = substr($uri, 8);
        }
        // Ditto, VIZ URLs.
        else if (strtolower($request->path[0]) == "homestuck")
        {
            $uri = substr($uri, 11);
        }

        $file = @self::$internalUriMap[strtolower($uri)] ?? null;
        if (!is_null($file))
        {
            $content = @file_get_contents($file);
            if (false === $content)
            {
                http_response_code(404);
                return;
            }
            $contentType = Network::getMimeType($file);
            header("Content-Type: $contentType");
            echo $content;
            return;
        }

        $response = null;
        if (!ServerConfig::isOfflineMode())
        {
            $url = @self::$externalUrlMap[strtolower($uri)] ?? null;
            if (!is_null($url))
            {
                $response = Network::urlRequest($url);
                goto outputResponse;
            }
        }

        $response = Network::mspaRequest($uri);

outputResponse:
        http_response_code($response->status);
        foreach ($response->headers as $name => $value)
        {
            if (!in_array($name, self::$illegalResponseHeaders))
                header("$name: $value");
        }
        echo $response->body;
    }

    public function post(RequestMetadata $request): void
    {
        $this->get($request);
    }
}