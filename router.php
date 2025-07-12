<?php
require "lib/config.php";
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
    // Cascade on map. Hussie is a fucking idiot, got the page number wrong. Even hardcoded an "END OF ACT 5"
    // link into cascade.php as a band-aid fix
    $str = str_replace("http://www.mspaintadventures.com/cascade.php?s=6&p=6009", "/read/6/006009", $str);
    $str = preg_replace('/"(?:http:\/\/www\.mspaintadventures\.com\/(?:(?:index|scratch|cascade|trickster|ACT6ACT5ACT1x2COMBO|ACT6ACT6)\.php|)|)\?s=(.*?)(?:&|&amp;)p=(.*?)"/m', "\"/read/$1/$2\"", $str);
    $str = preg_replace('/"(?:http:\/\/www\.mspaintadventures\.com\/(?:index\.php|)|)\?s=(.*?)"/m', "\"/read/$1\"", $str);
    $str = preg_replace('/"http:\/\/www\.mspaintadventures\.com\/storyfiles\/hs2\/waywardvagabond\/(.*?)\/"/m', "\"/waywardvagabond/$1\"", $str);
    $str = preg_replace('/"http:\/\/www\.mspaintadventures\.com\/sweetbroandhellajeff\/\?cid=0*([0-9]+)\.(?:jpg|gif)"/', "\"/sbahj/$1\"", $str);
    // For map:
    $str = str_replace("http://www.mspaintadventures.com/DOTA/", "/read/6/006715", $str);
    $str = str_replace("http://www.mspaintadventures.com/007395/", "/read/6/007395", $str);
    $str = str_replace("http://www.mspaintadventures.com/GAMEOVER/", "/read/6/008801", $str);
    $str = str_replace("http://www.mspaintadventures.com/shes8ack/", "/read/6/009305", $str);
    $str = str_replace("http://www.mspaintadventures.com/collide.html", "/read/6/009987", $str);
    $str = str_replace("http://www.mspaintadventures.com/ACT7.html", "/read/6/010027", $str);
    $str = str_replace("http://www.mspaintadventures.com/endcredits.html", "/read/6/010030", $str);
    $str = str_replace("http://www.mspaintadventures.com/sweetbroandhellajeff/", "/sbahj", $str);
    // This is regularly just a full MSPA url in plaintext.
    // I could hardcode the chadthundercock domain or use just
    // "/oilretcon" but both of those aren't really good solutions.
    // Just make it into a hyperlink. Selecting, copying, and pasting
    // a link is kind of inconvenient for mobile devices anyway.
    $str = str_replace(
        "http://www.mspaintadventures.com/oilretcon.html",
        '<a href="/oilretcon" style="color: #eeeeee">/oilretcon</a>',
        $str
    );
    $str = preg_replace("/http:\/\/(www\.|cdn\.|)mspaintadventures\.com\/(?!oilretcon\.html|storyfiles\/hs2\/waywardvagabond|sweetbroandhellajeff)/", "/mspa/", $str);
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
        case "archive":
            $template = "archive";
            break;
        case "options":
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $autologs = (isset($_POST["auto-open-logs"]) && $_POST["auto-open-logs"] == "on");
                $theme = $_POST["theme"];
                $theme_override = (isset($_POST["override-theme"]) && $_POST["override-theme"] == "on");

                setcookie("auto-open-logs", $autologs ? "true" : "false", time() + 34560000, "/"); // 400 days
                setcookie("theme", $theme, time() + 34560000, "/");
                setcookie("override-theme", $theme_override ? "true" : "false", time() + 34560000, "/");
            }
            else
            {
                $autologs = (isset($_COOKIE["auto-open-logs"]) && $_COOKIE["auto-open-logs"] == "true");
                $theme = $_COOKIE["theme"] ?? "default";
                $theme_override = (isset($_COOKIE["override-theme"]) && $_COOKIE["override-theme"] == "true");
            }

            $data->autologs_opt = $autologs;
            $data->theme_opt = $theme;
            $data->theme = ($theme == "default") ? null : $theme;
            $dont_change_theme = true;
            $data->theme_override = $theme_override;

            $template = "options";
            break;
        case "mspa":
            require "lib/mspa_funnel.php";
            mspa_funnel(substr($_SERVER["REQUEST_URI"], 6));
            break;
        case "jailbreak":
        case "bard-quest":
        case "blood-spade":
        case "problem-sleuth":
        case "beta":
        case "homestuck":
        case "ryanquest":
            $s = match ($routerUrl->path[0]) {
                "jailbreak" => "1",
                "bard-quest" => "2",
                "blood-spade" => "3",
                "problem-sleuth" => "4",
                "beta" => "5",
                "homestuck" => "6",
                "ryanquest" => "ryanquest"
            };

            $offset = match ($routerUrl->path[0]) {
                "jailbreak" => 1,
                "bard-quest" => 135,
                "blood-spade" => null,
                "problem-sleuth" => 218,
                "beta" => 1892,
                "homestuck" => 1900,
                "ryanquest" => 0
            };

            switch (count($routerUrl->path))
            {
                case 1:
                    header("Location: /read/6");
                    break;
                case 2:
                    $vp = $routerUrl->path[1];
                    if ($s == "3")
                        $p = "MC0001";
                    else if ($s == "1" && $vp == "135")
                        $p = "jb2_000000";
                    // Non-numeric pages (pony, darkcage)
                    else if (intval($vp) != $vp)
                        $p = $vp;
                    else
                        $p = str_pad(strval(intval($vp) + $offset), 6, "0", STR_PAD_LEFT);

                    header("Location: /read/$s/$p");
                    break;
                default:
                    http_response_code(404);
                    break;
            }
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
                // shes8ack
                else if ($p == "009305")
                {
                    $template = "shes8ack";
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
            // Fireflies
            else if (($ip >= 9000 && $ip <= 9024)
            || ($ip >= 9289 && $ip <= 9303))
            {
                $data->fireflies = true;
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
                case 4692: // [S] Past Karkat: Wake up.
                case 4979: // [S] John: Enter village.
                case 5221: // [S] Kanaya: Return to the core.
                case 5338: // [S] Equius: Seek the highb100d.
                case 5595: // [S] Seer: Descend.
                case 5617: // [S] Terezi: Proceed. (not playable, but you need to hit an arrow key to proceed with it)
                case 5660: // [S] Flip. (ditto)
                case 7163: // [S] ACT 6 INTERMISSION 3
                case 7208: // [S][A6I3] ==>
                case 7298: // [S][A6I3] ==>
                    $data->gamepad = true;
                    break;
                case 3258: // [S] ACT 4 ==>
                    $data->gamepad = "003258";
                    break;
                case 7614: // [S] ACT 6 ACT 5 ACT 2
                    $data->gamepad = "007614";
                    break;
                case 7640: // [S] ==> (trickster Roxy)
                    $data->gamepad = "007640";
                    break;
                case 7659: // [S] ==> (trickster Dirk)
                    $data->gamepad = "007659";
                    break;
            }

            $data->page = $page;
            if (isset($page2))
                $data->page2 = $page2;
            $data->s = $s;
            $data->p = $p;
            $template = $x2combo ? "read_x2_combo" : "read";
            if ($p == "009535")
                $template = "echidna";
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
        case "waywardvagabond":
            $IMAGE_COUNTS = [
                "anagitatedfinger" => 4,
                "anunsealedtunnel" => 7,
                "asentrywakens" => 5,
                "astudiouseye" => 6,
                "beneaththegleam" => 2,
                "preparesforcompany" => 1,
                "recordsastutteringstep" => 6,
                "windsdownsideways" => 7
            ];

            $id = $routerUrl->path[1] ?? null;
            if (count($routerUrl->path) != 2
            || !isset($IMAGE_COUNTS[$id]))
            {
                http_response_code(404);
                break;
            }

            $data->id = $id;
            $data->image_count = $IMAGE_COUNTS[$id];

            $template = "waywardvagabound";
            break;
        case "oilretcon":
            if (count($routerUrl->path) != 1)
            {
                http_response_code(404);
                break;
            }

            $template = "oilretcon";
            break;
        case "sbahj":
            $FIRST_COMIC = 1;
            $LAST_COMIC = 54;
            $comic = isset($routerUrl->path[1]) ? intval($routerUrl->path[1]) : $FIRST_COMIC;
            if (count($routerUrl->path) > 2
            || $comic < $FIRST_COMIC || $comic > $LAST_COMIC)
            {
                http_response_code(404);
                break;
            }

            $data->first = $FIRST_COMIC;
            $data->prev = max($FIRST_COMIC, $comic - 1);
            $data->next = min($LAST_COMIC, $comic + 1);
            $data->last = $LAST_COMIC;

            $GIFS = [ 21, 29, 34 ];
            $ext = ".jpg";
            if (in_array($comic, $GIFS))
                $ext = ".gif";
            
            // Twig doesn't have a filter for this, this is for convenience
            $data->img = str_pad($comic, 3, "0", STR_PAD_LEFT) . $ext;

            $template = "sbahj";
            break;
        case "sbahjthemovie1":
            if (count($routerUrl->path) != 1)
            {
                http_response_code(404);
                break;
            }

            $template = "SBAHJthemovie1";
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
    [
        "text" => "SHOP",
        "url" => "https://topatoco.com/collections/hussie",
        "color" => "yellow",
        "newtab" => true
    ],
    [
        "text" => "MUSIC",
        "url" => "https://homestuck.bandcamp.com/",
        "color" => "yellow",
        "newtab" => true
    ],
    [
        "text" => "OPTIONS",
        "url" => "/options",
        "color" => "orange"
    ],
    [
        "text" => "SECRETS",
        "url" => "http://www.mspaintadventures.com/unlock.html",
        "color" => "orange",
        "newtab" => true
    ],
    [
        "text" => "CREDITS",
        "url" => "http://www.mspaintadventures.com/credits.html",
        "color" => "orange",
        "newtab" => true
    ],
];

// Options
$data->autologs = (isset($_COOKIE["auto-open-logs"]) && $_COOKIE["auto-open-logs"] == "true");
$theme = $_COOKIE["theme"] ?? null;
$theme = ($theme == "default") ? null : $theme;
if ((!isset($dont_change_theme) || !$dont_change_theme)
&& (is_null($data->theme) || (isset($_COOKIE["override-theme"]) && $_COOKIE["override-theme"] == "true")))
{
    $data->theme = $theme;
}

$homosuck_link_overrides = [
    "WORTHLESS GARBAGE.",
    "STUPID.",
    "WHO CARES?",
    "WOW.",
    "NO.",
    "BORING.",
    "BULLSHIT.",
    "DUMB NOISE.",
    "WHO CARES?",
    "WHATEVER.",
    "MORONS."
];

if ($data->theme == "homosuck")
{
    for ($i = 0; $i < count($homosuck_link_overrides); $i++)
    {
        $data->links[$i]["text"] = $homosuck_link_overrides[$i];
    }
}

echo $twig->render($template . ".html", [$data]);
