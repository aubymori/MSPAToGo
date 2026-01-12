<?php
namespace MSPAToGo;

class Response
{
    public int $status;
    public array $headers;
    public string $body;
}

class Network
{
    public static function urlRequest(string $url): Response
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FOLLOWLOCATION => 0,
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => $_SERVER["HTTP_USER_AGENT"] . " MSPAToGo/1.0"
        ]);

        $cr = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($cr, 0, $headerSize);
        $body = substr($cr, $headerSize);
        $status = intval(substr($headers, 9, 3));

        // Split header array and skip HTTP response code
        // (e.g. HTTP/1.1 200 OK)
        $headers = str_replace("\r\n", "\n", $headers);
        $splitHeaders = explode("\n", $headers);
        array_shift($splitHeaders);
        
        $headerArr = [];
        foreach ($splitHeaders as $header)
        {
            $split = explode(":", $header);
            $name = strtolower(trim($split[0]));
            if ($name == "")
                continue;
            $value = trim(@$split[1] ?? "");
            $headerArr[$name] = $value;
        }

        $response = new Response;
        $response->status = $status;
        $response->headers = $headerArr;
        $response->body = $body;
        return $response;
    }

    public static function getMimeType(string $file): string
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

    /**
     * Performs a GET request to the MSPA server, or if in offline mode, gets
     * the according file.
     * 
     * @param string $path   Path on the MSPA server to get.
     * @param bool   $noCdn  If in online mode, do not try cdn.mspaintadventures.com.
     */
    public static function mspaRequest(string $path, bool $noCdn = false): Response
    {
        if (ServerConfig::isOfflineMode())
        {
            $content = @file_get_contents("mspa_local/$path");
            $response = new Response;
            if ($content === false)
            {
                $response->status = 404;
                $response->body = "";
                $response->headers = [
                    "content-type" => "text/html"
                ];
            }
            else
            {
                $response->status = 200;
                $response->headers = [
                    "content-type" => self::getMimeType($path)
                ];
                $response->body = $content;
            }
            return $response;
        }

        $url = ($noCdn ? "http://www.mspaintadventures.com/" : "http://cdn.mspaintadventures.com/") . $path;
        $response = self::urlRequest($url);
        if (!$noCdn && $response->status != 200)
            return self::mspaRequest($path, true);
        return $response;
    }
}