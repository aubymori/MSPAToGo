<?php
// Determines template to use and renders.

function betterParseUrl($url) {
    $purl = parse_url($url);
    $response = (object) [];
    foreach(explode(' ', 'scheme host port user pass fragment') as $elm) {
        if (isset($purl[$elm])) {
            $response->{$elm} = $purl[$elm];
        }
    }

    if (isset($purl['path'])) {
        $temp = explode('/', $purl['path']);
        if ($temp[0] === '') {
            array_splice($temp, 0, 1);
        }
        $response->path = $temp;
    }

    if (isset($purl['query'])) {
        $response->query = explode('&', $purl['query']);
    }

    return $response;
}

function replace_mspa_links(string &$str): void
{
    $str = preg_replace('/"(?:http:\/\/www.mspaintadventures.com\/(?:index.php|)|)\?s=(.*?)&p=(.*?)"/m', "\"/read/$1/$2\"", $str);
}

$routerUrl = betterParseUrl($_SERVER["REQUEST_URI"]);
$template = "404";
$data->theme = null;

if (isset($routerUrl->path[0]))
{
    switch (strtolower($routerUrl->path[0]))
    {
        case "":
            // Load auto-save
            if (isset($_COOKIE["autosave"])
            && isset($_COOKIE["s_cookie"]) && isset($_COOKIE["p_cookie"]))
            {
                header("Location: /read/" . $_COOKIE["s_cookie"] . "/" . $_COOKIE["p_cookie"]);
                die();
            }

            $template = "home";
            break;
        case "mspa":
            require "lib/mspa_funnel.php";
            mspa_funnel(substr($_SERVER["REQUEST_URI"], 6));
            break;
        case "read":
            // I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND
            // I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND
            // I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND
            // I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND
            // I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND I HATE OPENBOUND
            if (count($routerUrl->path) > 3 && $routerUrl->path[2] == "storyfiles")
            {
                require "lib/mspa_funnel.php";
                mspa_funnel(substr($_SERVER["REQUEST_URI"], 8));
            }

            $s = null;
            $p = null;
            if (count($routerUrl->path) == 3)
            {
                $s = $routerUrl->path[1];
                $p = $routerUrl->path[2];
            }
            // Retrieve first page statically
            else if (count($routerUrl->path) == 2)
            {
                $s = $routerUrl->path[1];
                $adventures = json_decode(file_get_contents("static/adventures.json"));
                //var_dump($adventures);
                foreach ($adventures as $adv)
                {
                    if ($adv->id == $s)
                    {
                        $p = $adv->firstPage;
                        break;
                    }
                }

                if (is_null($p))
                {
                    http_response_code(404);
                    break;
                }
            }
            else
            {
                http_response_code(404);
                break;
            }

            if ($s == "6")
            {
                if ($p == "006715")
                {
                    $template = "DOTA";
                    break;
                }
                else if ($p == "008801")
                {
                    $template = "GAMEOVER";
                    break;
                }
            }

            require "lib/page.php";
            $page = get_page_data($s, $p);
            if (is_null($page))
            {
                http_response_code(404);
                break;
            }

            // Auto-save
            if (isset($_COOKIE["autosave"]))
            {
                setcookie("s_cookie", $s, time() + 34560000, "/"); // 400 days
                setcookie("p_cookie", $p, time() + 34560000, "/");
            }

            $ip = intval($p);
            if ($ip == 6009)
            {
                $data->theme = "cascade";
                $data->banner = [
                    "image" => "/mspa/images/header_cascade.gif"
                ];
            }
            else if ($ip > 5663 && $ip < 5982)
            {
                $data->theme = "scratch";
                $scratch_banners = json_decode(file_get_contents("static/scratch_banners.json"));
                if (isset($scratch_banners->{$p}))
                {
                    $banner = $scratch_banners->{$p};
                    $data->banner = [
                        "image" => $banner->image,
                        "tooltip" => isset($banner->tooltip) ? $banner->tooltip : null,
                        "imgtip" => isset($banner->imgtip) ? $banner->imgtip : null,
                    ];
                }
            }
            else if ($ip == 5982)
            {
                $data->theme = "sbahj";
            }
            else if ($ip > 7613 && $ip < 7678)
            {
                $data->theme = "trickster";
            }
            else if ($ip == 9987)
            {
                $data->theme = "collide";
                $data->banner = [
                    "image" => "/mspa/images/collide_header.gif"
                ];
            }
            else if ($ip == 10027)
            {
                $data->theme = "act7";
                $data->banner = [
                    "image" => "/mspa/images/act7_header.gif"
                ];
            }

            switch ($ip)
            {
                case 2153: // [S] YOU THERE. BOY.
                case 2376: // [S] ==>
                case 3258: // [S] ACT 4 ==>
                case 4692: // [S] Past Karkat: Wake up.
                case 4979: // [S] John: Enter village.
                case 5221: // [S] Kanaya: Return to the core.
                case 5338: // [S] Equius: Seek the highb100d.
                case 5595: // [S] Seer: Descend.
                case 5617: // [S] Terezi: Proceed.
                case 7163: // [S] ACT 6 INTERMISSION 3
                case 7208: // [S][A6I3] ==>
                case 7298: // [S][A6I3] ==>
                    $data->gamepad = true;
            }

            $data->page = $page;
            $data->s = $s;
            $data->p = $p;
            $template = "read";

            break;
        case "log":
            if (count($routerUrl->path) > 3)
            {
                http_response_code(404);
                break;
            }

            if (count($routerUrl->path) >= 2)
            {
                $reverse = false;
                if (isset($routerUrl->path[2]))
                {
                    if (strtolower($routerUrl->path[2]) == "rev")
                    {
                        $reverse = true;
                    }
                    else
                    {
                        http_response_code(404);
                        break;
                    }
                }

                $s = $routerUrl->path[1];
            $text = "";
            $url = "http://www.mspaintadventures.com/logs/" . ($reverse ? "log_rev_" : "log_") . "$s.txt";
            $status = http_get($url, $text);
            if ($status != 200)
            {
                http_response_code(404);
                break;
            }
            replace_mspa_links($text);

            $data->reversed = $reverse;
            $data->log_html = $text;
            $data->s = $s;
            }
            else
            {
                $data->adventures = json_decode(file_get_contents("static/adventures.json"));
            }
            
            $template = "log";
            break;
        case "search":
            if (count($routerUrl->path) > 2)
            {
                http_response_code(404);
                break;
            }

            if (isset($routerUrl->path[1]))
            {
                $text = "";
                $search = $routerUrl->path[1];
                $status = http_get("http://www.mspaintadventures.com/search/search_$search.txt", $text);
                if ($status != 200)
                {
                    http_response_code(404);
                    break;
                }
                
                replace_mspa_links($text);
                $data->search_html = $text;
            }

            $template = "search";
            break;
            case "map":
                if (count($routerUrl->path) > 2)
                {
                    http_response_code(404);
                    break;
                }
    
                if (isset($routerUrl->path[1]))
                {
                    $text = "";
                    $map = $routerUrl->path[1];
                    $status = http_get("http://www.mspaintadventures.com/maps/$map.html", $text);
                    if ($status != 200)
                    {
                        http_response_code(404);
                        break;
                    }
                    
                    replace_mspa_links($text);
                    $data->map_html = $text;
                }
                else
                {
                    $data->adventures = json_decode(file_get_contents("static/adventures.json"));
                }
                $template = "map";
                break;
        default:
            http_response_code(404);
            $template = "404";
            break;
    }
}
else
{
    http_response_code(404);
    $template = "404";
}

// Init static data shared across all pages
$data->links = [
    [
        "text" => "MSPA TO GO",
        "url" => "/",
        "color" => "white"
    ],
    [
        "text" => "ARCHIVE",
        "url" => "/archive",
        "color" => "green"
    ],
    [
        "text" => "GITHUB",
        "url" => "https://github.com/aubymori/MSPAToGo",
        "color" => "green",
        "newtab" => true
    ],
    [
        "text" => "MAP",
        "url" => "/map",
        "color" => "blue"
    ],
    [
        "text" => "LOG",
        "url" => "/log",
        "color" => "blue"
    ],
    [
        "text" => "SEARCH",
        "url" => "/search",
        "color" => "blue"
    ],
];

echo $twig->render($template . ".html", [$data]);