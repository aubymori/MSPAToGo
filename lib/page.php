<?php

function get_http_response_code($url)
{
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

function get_page_data(string $s, string $p, bool $ignore_commands = false, bool $ignore_back = false): object|null
{
    $response = (object)[];
    $text = "";
    $url = "http://www.mspaintadventures.com/$s/$p.txt";
    $status = http_get($url, $text);
    if ($status != 200)
        return null;

    // Neutralize line endings and split data
    $text = str_replace("\r\n", "\n", $text);
    $split = explode("\n###\n", $text);

    // Title
    $response->title = $split[0];

    // Media
    $medias = explode("\n", $split[3]);
    $response->media = [];

    switch ($p)
    {
        // Cascade is a special case. It was a supercartridge page
        // before supercartridgees were officially a thing, and thus
        // the specialties of it are baked into the cascade.php file.
        // Just make it into a supercartridge page from a static HTML
        // file.
        case "006009":
            $response->supercartridge = true;
            $response->media[] = [
                "type" => "supercartridge",
                "html" => file_get_contents("static/cascade.html")
            ];
            break;
        case "009987":
            $response->supercartridge = true;
            $response->media[] = [
                "type" => "supercartridge",
                "html" => file_get_contents("static/collide.html")
            ];
            break;
        case "0010027":
            $response->supercartridge = true;
            $response->media[] = [
                "type" => "supercartridge",
                "html" => file_get_contents("static/ACT7.html")
            ];
            break;
            break;
        default:
            foreach ($medias as $media)
            {
                $media = preg_replace("/http:\/\/(www\.|cdn\.|)mspaintadventures\.com\//", "/mspa/", $media);
                // Flash
                if (substr($media, 0, 2) == "F|")
                {
                    $media = substr(trim($media), 2);
                    $flash_filename = substr($media, -5);
                    $flash_link = "$media/$flash_filename";
                    $js_link = "$media/AC_RunActiveContent.js";

                    $flash_height = 450;
                    $flash_height_map = json_decode(file_get_contents("static/flash_heights.json"));
                    if (isset($flash_height_map->{$p}))
                        $flash_height = $flash_height_map->{$p};

                    $response->media[] = [
                        "type" => "flash",
                        "url" => $flash_link,
                        "js_url" => $js_link,
                        "width" => 650,
                        "height" => $flash_height
                    ];
                }
                // Supercartridge
                else if (substr($media, 0, 2) == "S|")
                {
                    $response->supercartridge = true;
                    $media = substr(trim($media), 2);
                    $media = str_replace("/mspa/", "http://cdn.mspaintadventures.com/", $media);
                    $text = "";
                    $status = http_get($media . "/index.html", $text);
                    if ($status != 200)
                        return null;
        
                    $text = preg_replace("/http:\/\/(www\.|cdn\.|)mspaintadventures\.com\//", "/mspa/", $text);
                    $response->media[] = [
                        "type" => "supercartridge",
                        "html" => $text
                    ];
                }
                // JS game
                else if (substr($media, 0, 2) == "J|")
                {
                    $pint = intval($p);
                    $media = substr(trim($media), 2);
                    // thanks hussie for changing it after using openbound once
                    $xml_url = $media . "/levels/" . (($pint == 7163) ? "openbound/openbound.xml" : "init.xml");
                    $js_url = $media . "/Sburb.min.js";
                    $response->media[] = [
                        "type" => "jternia",
                        "xml_url" => $xml_url,
                        "js_url" => $js_url
                    ];
                }
                // GIF
                else
                {
                    $response->media[] = [
                        "type" => "image",
                        "url" => $media
                    ];
                }
            }
            break;
    }

    // Text
    $text = trim($split[4]);
    $log_type = substr($text, 0, 11);
    $log_name = null;
    switch ($log_type)
    {
        case "|PESTERLOG|":
            $log_name = "Pesterlog";
            break;
        case "|SPRITELOG|":
            $log_name = "Spritelog";
            break;
        case "|JOURNALOG|":
            $log_name = "Journalog";
            break;
        case "|RECAP LOG|":
            $log_name = "Recap log";
            break;
        case "|SRIOUSBIZ|":
            $log_name = "Serious Business";
            break;
        case "|DIALOGLOG|":
            $log_name = "Dialoglog";
            break;
        case "|AUTHORLOG|":
            $log_name = "Authorlog";
            break;
        case "|TRKSTRLOG|":
            $log_name = "Tricksterlog";
            break;
    }

    if (!is_null($log_name))
    {
        $response->log_type = $log_type;
        $response->log_name = $log_name;
        $response->text = trim(substr($text, 11));
    }
    else
    {
        $response->text = $text;
    }

    if (!$ignore_commands)
    {
        $response->commands = [];
        foreach (explode("\n", $split[5]) as $cid)
        {
            // MSPA never uses anything but X and the list always ends at it,
            // but oh well. Compatibility.
            if ($cid == "X" || $cid == "O" || $cid == "?")
                break;

            $cmd_url = "http://www.mspaintadventures.com/$s/$cid.txt";
            $text = file_get_contents($cmd_url);
            $text = str_replace("\r\n", "\n", $text);
            $title = explode("\n###\n", $text)[0];
            $response->commands[] = [
                "title" => $title,
                "page" => $cid
            ];
        }
    }

    if (!$ignore_back)
    {
        // Previous page
        $back_url = "http://www.mspaintadventures.com/{$s}_back/$p.txt";
        $back_text = "";
        $back_status = http_get($back_url, $back_text);
        if ($back_status == 200)
            $response->prev_page = $back_text;
    }

    return $response;
}