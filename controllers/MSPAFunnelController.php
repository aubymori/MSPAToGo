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

        $response = Network::mspaRequest($uri);
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