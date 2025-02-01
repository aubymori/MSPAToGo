<?php

function get_http_response_code($url)
{
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

function get_page_data(string $s, string $p): object|null
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
    foreach ($medias as $media)
    {
        $media = preg_replace("/http:\/\/(www|cdn)\.mspaintadventures\.com\//", "/mspa/", $media);
        // Flash
        if (substr($media, 0, 2) == "F|")
        {

        }
        // Supercartridge
        else if (substr($media, 0, 2) == "S|")
        {

        }
        // JS game
        else if (substr($media, 0, 2) == "J|")
        {

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

    // Commands (next page)
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

    // Previous page
    $back_url = "http://www.mspaintadventures.com/{$s}_back/$p.txt";
    $back_text = "";
    $back_status = http_get($back_url, $back_text);
    if ($back_status == 200)
        $response->prev_page = $back_text;

    return $response;
}