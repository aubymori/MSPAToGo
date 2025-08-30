<?php
namespace MSPAToGo\Controller;

use MSPAToGo\MSPALinks;
use MSPAToGo\Network;
use MSPAToGo\Options;
use MSPAToGo\RequestMetadata;
use MSPAToGo\ServerConfig;
use OCILob;

class ReadController extends PageController
{
    public string $template = "read";

    private static array $passwordPages = [
        "009058", "009109", "009135", "009150",
        "009188", "009204", "009222", "009263"
    ];

    private static array $fullPageFlashes = [
        "006715" => [
            "title"   => "DOTA",
            "swfUrl"  => "/mspa/DOTA/04812",
            "jsUrl"   => "/mspa/DOTA/AC_RunActiveContent.js",
            "width"   => 950,
            "height"  => 650,
            "bgcolor" => "#000000"
        ],
        "007395" => [
            "swfUrl"  => "/mspa/007395/05492",
            "jsUrl"   => "/mspa/007395/AC_RunActiveContent.js",
            "width"   => 950,
            "height"  => 1160,
            "bgcolor" => "#535353"
        ],
        "007680" => [
            "swfUrl"  => "/mspa/007680/05777_2",
            "jsUrl"   => "/mspa/007680/AC_RunActiveContent.js",
            "width"   => 950,
            "height"  => 1160,
            "bgcolor" => "#535353"
        ],
        "008801" => [
            "title"   => "GAME OVER",
            "swfUrl"  => "/mspa/storyfiles/hs2/GAMEOVER/06898",
            "jsUrl"   => "/mspa/GAMEOVER/AC_RunActiveContent.js",
            "width"   => 950,
            "height"  => 786,
            "bgcolor" => "#042300"
        ],
        "009305" => [
            "title"   => "shes8ack",
            "swfUrl"  => "/mspa/shes8ack/07402",
            "jsUrl"   => "/mspa/shes8ack/AC_RunActiveContent.js",
            "width"   => 950,
            "height"  => 650,
            "bgcolor" => "#ffffff"
        ]
    ];

    // min page, max page, name
    private static array $themedPages = [
        [ 5664,  5981,   "scratch" ],
        [ 6009,  6009,   "cascade" ],
        [ 5982,  5982,     "sbahj" ],
        [ 7614,  7677, "trickster" ],
        [ 8143,  8177,  "homosuck" ],
        [ 8375,  8430,  "homosuck" ],
        [ 8753,  8801,  "homosuck" ],
        [ 8821,  8843,  "homosuck" ],
        [ 9309,  9347,  "homosuck" ],
        [ 9987,  9987,   "collide" ],
        [ 10027, 10027,     "act7" ],
    ];

    // min page, max page, image, desktop image
    private static array $pageBanners = [
        [ 6009,  6009,      "/mspa/images/header_cascade.gif" ],
        [ 9987,  9987,      "/mspa/images/collide_header.gif" ],
        [ 10027, 10027,        "/mspa/images/act7_header.gif" ],
        [ 7688,  7825,  "/assets/img/act6act5act1x2combo.gif", "/assets/img/act6act5act1x2combo_desktop.gif" ],
    ];

    private static array $hscrollPages = [
        "008848", "008850", "008857"
    ];

    // min page, max page
    private static array $fireflyPages = [
        [ 9000, 9024 ],
        [ 9298, 9303 ]
    ];

    private static array $extraBodyClasses = [
        "009987" => [ "collide" ],
        "010027" => [ "act7" ],
    ];

    // general gamepad (up down left right space)
    private const GAMEPAD_GENERAL = 0;
    // page-specific gamepad
    private const GAMEPAD_SPECIFIC = 1;

    private static array $gamepadPages = [
        "002153" => self::GAMEPAD_GENERAL,  // [S] YOU THERE. BOY.
        "002376" => self::GAMEPAD_SPECIFIC, // [S] ==> (imp pogo game, just left+right)
        "003258" => self::GAMEPAD_SPECIFIC, // [S] ACT 4 ==> (shift toggle)
        "004692" => self::GAMEPAD_GENERAL,  // [S] Past Karkat: Wake up.
        "004979" => self::GAMEPAD_GENERAL,  // [S] John: Enter village.
        "005221" => self::GAMEPAD_GENERAL,  // [S] Kanaya: Return to the core
        "005338" => self::GAMEPAD_GENERAL,  // [S] Equius: Seek the highb100d.
        "005595" => self::GAMEPAD_GENERAL,  // [S] Seer: Descend.
        "005617" => self::GAMEPAD_GENERAL,  // [S] Terezi: Proceed. (not playable, but you need to hit an arrow key to proceed with it)
        "005660" => self::GAMEPAD_GENERAL,  // [S] Flip. (ditto)
        "007163" => self::GAMEPAD_GENERAL,  // [S] ACT 6 INTERMISSION 3
        "007208" => self::GAMEPAD_GENERAL,  // [S][A6I3] ==>
        "007614" => self::GAMEPAD_SPECIFIC, // [S] ACT 6 ACT 5 ACT 2 (just space)
        "007640" => self::GAMEPAD_SPECIFIC, // [S] ==> (trickster Roxy, just left+right)
        "007659" => self::GAMEPAD_SPECIFIC, // [S] ==> (trickster Dirk, just right)
        "007298" => self::GAMEPAD_GENERAL,  // [S][A6I3] ==>
    ];

    public function onGet(RequestMetadata $request): bool
    {
        if (Options::get("viz-links") && $request->path[0] == "read")
        {
            $viz = MSPALinks::mspaToViz($request->path[1], @$request->path[2] ?? null);
            if (is_null($viz))
                return false;
            $url = "/" . $viz->s;
            if (isset($viz->p))
                $url .= "/" . $viz->p;
            header("Location: $url");
            return true;
        }
        else if (!Options::get("viz-links") && $request->path[0] != "read")
        {
            $mspa = MSPALinks::vizToMspa($request->path[0], @$request->path[1] ?? null);
            if (is_null($mspa))
                return false;
            $url = "/read/" . $mspa->s;
            if (isset($mspa->p))
                $url .= "/" . $mspa->p;
            header("Location: $url");
            return true;
        }

        // Parse s and p
        $s = null; $p = null;
        if ($request->path[0] == "read")
        {
            // /read/s/p
            if (count($request->path) == 3)
            {
                $s = $request->path[1];
                $p = $request->path[2];
            }
            // /read/s (go to first page)
            else if (count($request->path) == 2)
            {
                $s = $request->path[1];
            }
            else
            {
                return false;
            }
        }
        else
        {
            if (count($request->path) != 1
            && count($request->path) != 2)
                return false;

            $viz = MSPALinks::vizToMspa($request->path[0], @$request->path[1] ?? null);
            if (is_null($viz))
                return false;
            $s = $viz->s;
            if (isset($viz->p))
                $p = $viz->p;
        }

        // Get first page if we don't have a p already
        if (is_null($p))
        {
            $adventures = json_decode(file_get_contents("static/adventures.json"));
            foreach ($adventures as $adv)
            {
                if ($adv->id == $s)
                {
                    $p = $adv->firstPage;
                    break;
                }
            }

            if (is_null($p))
                return false;
        }

        if ($s == "6")
        {   
            if ($p == "010030")
            {
                $this->template = "endcredits";
                $this->usePageframe = false;
                return true;
            }

            if (Options::get("desktop"))
            {
                if ($p == "009987")
                {
                    $this->template = "collide_desktop";
                    $this->usePageframe = false;
                    return true;
                }
                else if ($p == "010027")
                {
                    $this->template = "ACT7_desktop";
                    $this->usePageframe = false;
                    return true;
                }
            }

            if (isset(self::$fullPageFlashes[$p]))
            {
                $data = self::$fullPageFlashes[$p];
                if (isset($data["title"]))
                    $this->data->title = $data["title"];
                $this->data->swf_url = $data["swfUrl"];
                $this->data->js_url  = $data["jsUrl"];
                $this->data->width   = $data["width"];
                $this->data->height  = $data["height"];
                $this->data->bgcolor = $data["bgcolor"];

                $this->template = "full_page_flash";
                $this->usePageframe = false;
                return true;
            }

            // Terezi passwords
            if (in_array($p, self::$passwordPages))
            {
                if (isset($_GET["pw"]))
                {
                    $newp = match(strtolower($_GET["pw"]))
                    {
                        "home"    => "009059",
                        "r3un1on" => "009110",
                        "fr4m3d"  => "009136",
                        "mom3nt"  => "009151",
                        "murd3r"  => "009189",
                        "just1c3" => "009205",
                        "honk"    => "009223",
                        "fl1p"    => "009264",
                        default    => null
                    };

                    if (!is_null($newp))
                        header("Location: /read/6/$newp");

                    $this->data->wrong_pw = true;
                }

                $this->template = "password";
                return true;
            }
        }

        $pageNum = intval($p);
        // ACT 6 ACT 5 ACT 1 x2 COMBO!!!
        if ($s == "6" && $pageNum >= 7688 && $pageNum <= 7825)
        {
            // Make sure we're not combining two unrelated pages
            if ($pageNum % 2 != 0)
            {
                $redirp = str_pad(strval(intval($p) - 1), 6, "0", STR_PAD_LEFT);
                header("Location: /read/$s/$redirp");
                return true;
            }

            $page = self::getPageData($s, $p, true);
            if (is_null($page))
                return false;

            $p2 = str_pad(strval(intval($p) + 1), 6, "0", STR_PAD_LEFT);
            $page2 = self::getPageData($s, $p2, false, true);
            if (is_null($page2))
                return false;

            $this->template = Options::get("desktop") ? "read_x2_combo_desktop" : "read_x2_combo";
            $this->data->x2combo = true;
        }
        else
        {
            $page = self::getPageData($s, $p);
            if (is_null($page))
                return false;
        }

        $this->data->page = $page;
        if (isset($page2))
            $this->data->page2 = $page2;
        $this->data->s = $s;
        $this->data->p = $p;

        $this->title = htmlspecialchars_decode($page->title);
        $this->description = htmlspecialchars_decode($page->text);

        if ($s =="6" && $p == "009535")
        {
            $this->usePageframe = false;
            $this->template = "echidna";
        }

        // Auto-save
        if (isset($_COOKIE["autosave"]))
        {
            setcookie("s_cookie", $s, time() + 34560000, "/"); // 400 days
            setcookie("p_cookie", $p, time() + 34560000, "/");
        }

        // If everything went right, we can set up themes and banners
        if ($s != "6" || $pageNum == 0)
            return true;

        foreach (self::$themedPages as $themeDef)
        {
            if ($pageNum >= $themeDef[0] && $pageNum <= $themeDef[1])
            {
                $this->data->theme = $themeDef[2];
                break;
            }
        }

        foreach (self::$pageBanners as $bannerDef)
        {
            if ($pageNum >= $bannerDef[0] && $pageNum <= $bannerDef[1])
            {
                $banner = $bannerDef[2];
                if (Options::get("desktop") && isset($bannerDef[3]))
                    $banner = $bannerDef[3];
                $this->data->banner = [
                    "image" => $banner
                ];
            }
        }

        // Scratch banners
        if ($pageNum >= 5664 && $pageNum <= 5981)
        {
            $scratchBanners = json_decode(file_get_contents("static/scratch_banners.json"));
            if (isset($scratchBanners->{$p}))
            {
                $banner = $scratchBanners->{$p};
                $this->data->banner = [
                    "image" => $banner->image,
                    "tooltip" => isset($banner->tooltip) ? $banner->tooltip : null,
                    "imgtip" => isset($banner->imgtip) ? $banner->imgtip : null,
                ];
            }
        }

        if (in_array($p, self::$hscrollPages))
            $this->data->hscroll = true;

        foreach (self::$fireflyPages as $fireflyDef)
        {
            if ($pageNum >= $fireflyDef[0] && $pageNum <= $fireflyDef[1])
            {
                $this->data->fireflies = true;
            }
        }

        if (isset(self::$gamepadPages[$p]))
        {
            $this->data->gamepad = (self::$gamepadPages[$p] == self::GAMEPAD_SPECIFIC)
                ? $p
                : true;
        }

        if (isset(self::$extraBodyClasses[$p]))
        {
            $this->data->extra_body_classes = self::$extraBodyClasses[$p];
        }
        
        return true;
    }

    private static array $customPages = [
        "006009" => [
            "path" => "static/cascade.html"
        ],
        "009987" => [
            "path"        => "static/collide.html",
            "offlinePath" => "static/collide_offline.html"
        ],
        "010027" => [
            "path"        => "static/ACT7.html",
            "offlinePath" => "static/ACT7_offline.html"
        ],
    ];


    private static function getPageData(
        string $s,
        string $p,
        bool   $ignoreCommands = false,
        bool   $ignoreBack = false): ?object
    {
        $response = (object)[];
        $pageResponse = Network::mspaRequest("$s/$p.txt", true);
        if ($pageResponse->status != 200)
            return null;

        // Neutralize line endings and split data
        $split = explode("\n###\n", str_replace("\r\n", "\n", $pageResponse->body));

        // Title
        $response->title = $split[0];

        // Media
        $response->media = [];
        if (isset(self::$customPages[$p]))
        {
            $customPage = self::$customPages[$p];
            $path = (ServerConfig::isOfflineMode() && isset($customPage["offline_path"]))
                ? $customPage["offline_path"]
                : $customPage["path"];
            $response->supercartridge = true;
            $response->media[] = [
                "type" => "supercartridge",
                "html" => file_get_contents($path)
            ];
        }
        else
        {
            $medias = explode("\n", $split[3]);
            foreach ($medias as $media)
            {
                $media = preg_replace("/http:\/\/(www\.|cdn\.|)mspaintadventures\.com\//", "/mspa/", $media);
                switch (substr($media, 0, 2))
                {
                    // Flash
                    case "F|":
                    {
                        $media = substr(trim($media), 2);
                        $flashFilename = substr($media, -5);
                        $flashLink = "$media/$flashFilename";
                        $jsLink = "$media/AC_RunActiveContent.js";

                        $flashHeight = 450;
                        $flashHeightMap = json_decode(file_get_contents("static/flash_heights.json"));
                        if (isset($flashHeightMap->{$p}))
                            $flashHeight = $flashHeightMap->{$p};

                        $response->media[] = [
                            "type" => "flash",
                            "url" => $flashLink,
                            "js_url" => $jsLink,
                            "width" => 650,
                            "height" => $flashHeight
                        ];
                        break;
                    }
                    // Supercartridge
                    case "S|":
                    {
                        $response->supercartridge = true;
                        $media = substr(trim($media), 2);
                        $media = str_replace("/mspa/", "", $media);

                        $scResponse = Network::mspaRequest($media . "/index.html");
                        if ($scResponse->status != 200)
                            return null;

                        $text = preg_replace("/http:\/\/(www\.|cdn\.|)mspaintadventures\.com\//", "/mspa/", $scResponse->body);
                        $response->media[] = [
                            "type" => "supercartridge",
                            "html" => $text
                        ];
                        break;
                    }
                    // JS game
                    case "J|":
                    {
                        $media = substr(trim($media), 2);
                        $xmlUrl = $media . "/levels/" . (($p == "007163" ? "openbound/openbound.xml" : "init.xml"));
                        $jsUrl = $media . "/Sburb.min.js";
                        $response->media[] = [
                            "type" => "jternia",
                            "xml_url" => $xmlUrl,
                            "js_url" => $jsUrl
                        ];
                        break;
                    }
                    // Image
                    default:
                        // really hacky but whatever man
                        if (!Options::get("desktop") && $media == "/mspa/storyfiles/hs2/05423_2.gif")
                        {
                            $response->media[] = [
                                "type" => "supercartridge",
                                "html" => file_get_contents("static/transcripts/storyfiles/hs2/05423_2.gif.html")
                            ];
                        }
                        else
                        {
                            $response->media[] = [
                                "type" => "image",
                                "url" => $media
                            ];
                        }
                        break;                        
                }
            }
        }

        // Page text
        $text = trim($split[4]);
        $logType = substr($text, 0, 11);
        $logName = match ($logType)
        {
            "|PESTERLOG|" => "Pesterlog",
            "|SPRITELOG|" => "Spritelog",
            "|JOURNALOG|" => "Journalog",
            "|RECAP LOG|" => "Recap log",
            "|SRIOUSBIZ|" => "Serious Business",
            "|DIALOGLOG|" => "Dialoglog",
            "|AUTHORLOG|" => "Authorlog",
            "|TRKSTRLOG|" => "Tricksterlog",
            default => null
        };

        if (!is_null($logName))
        {
            $response->log_type = $logType;
            $response->log_name = $logName;
            $response->text = trim(substr($text, 11));
        }
        else
        {
            $response->text = $text;
        }

        $response->text = MSPALinks::replaceMspaLinks($response->text);

        // Transcriptions
        $matches = null;
        if (!Options::get("desktop") && preg_match_all("/<img\s[^>]*?src\s*=\s*['\"]([^'\"]*?)['\"][^>]*?>/", $response->text, $matches, PREG_SET_ORDER))
        {
            foreach ($matches as $match)
            {
                $file = "static/transcripts/" . substr($match[1], 6) . ".html";
                if (file_exists($file))
                {
                    // i love how this has to be a ref. amazing.
                    $count = 1;
                    $transcript = file_get_contents($file);
                    $response->text = str_replace($match[0], $transcript, $response->text, $count);
                }
            }
        }

        if (!$ignoreCommands)
        {
            $response->commands = [];
            foreach (explode("\n", $split[5]) as $cid)
            {
                $cid = trim($cid);
                // MSPA never uses anything but X and the list always ends at it,
                // but oh well. Compatibility.
                if ($cid == "X" || $cid == "O" || $cid == "?")
                    break;

                $cmdResponse = Network::mspaRequest("$s/$cid.txt", true);
                if ($cmdResponse->status != 200)
                    return null;
                $title = explode("\n###\n", str_replace("\r\n", "\n", $cmdResponse->body))[0];
                $response->commands[] = [
                    "title" => $title,
                    "page" => $cid
                ];
            }
        }

        if (!$ignoreBack)
        {
            $backResponse = Network::mspaRequest("{$s}_back/{$p}.txt", true);
            // It's fine if we don't get 200 here, just means the page has no previous page
            if ($backResponse->status == 200)
            {
                $response->prev_page = trim($backResponse->body);
            }
        }

        return $response;
    }
}