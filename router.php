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
                // DOTA
                if ($p == "006715")
                {
                    $template = "DOTA";
                    break;
                }
                // Caliborn hitting MSPA with crowbar
                else if ($p == "007395")
                {
                    $template = "caliborn_crowbar";
                    break;
                }
                // Caliborn hitting MSPA with crowbar TWO
                else if ($p == "007680")
                {
                    $template = "caliborn_crowbar2";
                    break;
                }
                // GAME OVER
                else if ($p == "008801")
                {
                    $template = "GAMEOVER";
                    break;
                }
                // End credits
                else if ($p == "010030")
                {
                    $template = "endcredits";
                    break;
                }
                // Terezi passwords
                else if ($p == "009058"
                || $p == "009109"
                || $p == "009135"
                || $p == "009150"
                || $p == "009188"
                || $p == "009204"
                || $p == "009222"
                || $p == "009263")
                {
                    if (isset($_GET["pw"]))
                    {
                        $pw = strtolower($_GET["pw"]);
                        switch (strtolower($pw))
                        {
                            // real password from mspa, even viz kept it
                            // keeping for funsies :3
                            case "testpass":
                                header("Location: /");
                                break;
                            case "home":
                                header("Location: /read/6/009059");
                                break;
                            case "r3un1on":
                                header("Location: /read/6/009110");
                                break;
                            case "fr4m3d":
                                header("Location: /read/6/009136");
                                break;
                            case "mom3nt":
                                header("Location: /read/6/009151");
                                break;
                            case "murd3r":
                                header("Location: /read/6/009189");
                                break;
                            case "just1c3":
                                header("Location: /read/6/009205");
                                break;
                            case "honk":
                                header("Location: /read/6/009223");
                                break;
                            case "fl1p":
                                header("Location: /read/6/009264");
                                break;
                            case "":
                                break;
                            default:
                                $data->wrong_pw = true;
                                break;
                        }
                    }

                    $template = "password";
                    
                    break;
                }
            }

            // Auto-save
            if (isset($_COOKIE["autosave"]))
            {
                setcookie("s_cookie", $s, time() + 34560000, "/"); // 400 days
                setcookie("p_cookie", $p, time() + 34560000, "/");
            }

            $ip = intval($p);
            $x2combo = false;
            // Cascade
            if ($ip == 6009)
            {
                $data->theme = "cascade";
                $data->banner = [
                    "image" => "/mspa/images/header_cascade.gif"
                ];
            }
            // Scratch interlude
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
            // SNOP
            else if ($ip == 5982)
            {
                $data->theme = "sbahj";
            }
            // Trickster mode
            else if ($ip > 7613 && $ip < 7678)
            {
                $data->theme = "trickster";
            }
            // Collide
            else if ($ip == 9987)
            {
                $data->theme = "collide";
                $data->banner = [
                    "image" => "/mspa/images/collide_header.gif"
                ];
            }
            // ACT 7
            else if ($ip == 10027)
            {
                $data->theme = "act7";
                $data->banner = [
                    "image" => "/mspa/images/act7_header.gif"
                ];
            }
            // ACT 6 ACT 5 ACT 1 x2 COMBO!!!
            else if ($ip > 7687 && $ip < 7826)
            {
                // Make sure we're not combining two unrelated pages
                if ($ip % 2 != 0)
                {
                    $redirp = str_pad(strval(intval($p) - 1), 6, "0", STR_PAD_LEFT);
                    header("Location: /read/$s/$redirp");
                    break;
                }
                $x2combo = true;
                $data->banner = [
                    "image" => "/assets/img/act6act5act1x2combo.gif"
                ];
            }
            // HOMOSUCK
            else if (($ip > 8142 && $ip < 8178)
            || ($ip > 8374 && $ip < 8431)
            || ($ip > 8752 && $ip < 8802)
            || ($ip > 8820 && $ip < 8844)
            || ($ip > 9308 && $ip < 9348))
            {
                $data->theme = "homosuck";
            }
            // hscroll
            else if ($ip == 8848
            || $ip == 8850
            || $ip == 8857)
            {
                $data->hscroll = true;
            }

            require "lib/page.php";
            $page = get_page_data($s, $p, $x2combo);
            if (is_null($page))
            {
                http_response_code(404);
                break;
            }

            // Get second page for A6A5A1x2
            if ($x2combo)
            {
                $p2 = str_pad(strval(intval($p) + 1), 6, "0", STR_PAD_LEFT);
                $page2 = get_page_data($s, $p2, false, true);
                if (is_null($page2))
                {
                    http_response_code(404);
                    break;
                }
            }

            // Gamepad for walkarounds
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
                case 5617: // [S] Terezi: Proceed. (not playable, but you need to hit an arrow key to proceed with it)
                case 7163: // [S] ACT 6 INTERMISSION 3
                case 7208: // [S][A6I3] ==>
                case 7298: // [S][A6I3] ==>
                    $data->gamepad = true;
            }

            $data->page = $page;
            if (isset($page2))
                $data->page2 = $page2;
            $data->s = $s;
            $data->p = $p;
            $template = $x2combo ? "read_x2_combo" : "read";

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

$homosuck_link_overrides = [
    "WORTHLESS GARBAGE.",
    "STUPID.",
    "BULLSHIT.",
    "WOW.",
    "NO.",
    "BORING."
];

if ($data->theme == "homosuck")
{
    for ($i = 0; $i < count($homosuck_link_overrides); $i++)
    {
        $data->links[$i]["text"] = $homosuck_link_overrides[$i];
    }
}

echo $twig->render($template . ".html", [$data]);