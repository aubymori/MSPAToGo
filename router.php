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

$routerUrl = betterParseUrl($_SERVER["REQUEST_URI"]);
$template = "404";
$data->theme = null;

if (isset($routerUrl->path[0]))
{
    switch ($routerUrl->path[0])
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
            break;
        case "read":
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

            $data->page = $page;
            $data->s = $s;
            $data->p = $p;
            $template = "read";

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