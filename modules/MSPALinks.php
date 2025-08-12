<?php
namespace MSPAToGo;

class MSPALinks
{
    private static array $vizToMspaStory = [
        "jailbreak"      => "1",
        "bard-quest"     => "2",
        "blood-spade"    => "3",
        "problem-sleuth" => "4",
        "beta"           => "5",
        "homestuck"      => "6",
        "ryanquest"      => "ryanquest",
    ];

    // offsets
    private static array $vizToMspaPage = [
        "jailbreak"      => 1,
        "bard-quest"     => 135,
        "blood-spade"    => null,
        "problem-sleuth" => 218,
        "beta"           => 1892,
        "homestuck"      => 1900,
        "ryanquest"      => 0,
    ];

    public static function registerTwigFunctions(): void
    {
        $construct_mspa_link = new \Twig\TwigFunction("construct_mspa_link", function(string $s, ?string $p = null): string
        {
            return MSPALinks::constructMspaLink($s, $p);
        });
        ControllerManager::$twig->addFunction($construct_mspa_link);

        // For oilretcon
        $homestuck_page_number = new \Twig\TwigFunction("homestuck_page_number", function(int $mspaP): int
        {
            if (!Options::get("viz-links"))
                return $mspaP;
            return $mspaP - 1900;
        });
        ControllerManager::$twig->addFunction($homestuck_page_number);
    }

    public static function vizToMspa(string $s, ?string $p = null): ?object
    {
        $result = (object)[];
        if (!isset(self::$vizToMspaStory[$s]))
            return null;

        $result->s = self::$vizToMspaStory[$s];

        if (!is_null($p))
        {
            if ($s == "blood-spade")
            {
                $result->p = "MC0001";
            }
            else if ($s == "jailbreak" && $p == "135")
            {
                $result->p = "jb2_000000";
            }
            else if (intval($p) != $p)
            {
                $result->p = $p;
            }
            else
            {
                $result->p = str_pad(strval(intval($p) + self::$vizToMspaPage[$s]), 6, "0", STR_PAD_LEFT);
            }
        }

        return $result;
    }

    public static function mspaToViz(string $s, ?string $p = null): ?object
    {
        $result = (object)[];
        foreach (self::$vizToMspaStory as $vizS => $mspaS)
        {
            if ($mspaS == $s)
            {
                $result->s = $vizS;
                break;
            }
        }
        if (!isset($result->s))
            return null;

        if (!is_null($p))
        {
            if ($s == "3")
            {
                $result->p = "1";
            }
            else if ($s == "1" && $p == "jb2_000000")
            {
                $result->p = "135";
            }
            else if (intval($p) != $p)
            {
                $result->p = $p;
            }
            else
            {
                $result->p = intval($p) - self::$vizToMspaPage[$result->s];
            }
        }
        
        return $result;
    }

    public static function constructMspaLink(string $s, ?string $p = null): ?string
    {
        if (Options::get("viz-links"))
        {
            $viz = self::mspaToViz($s, $p);
            if (is_null($viz))
                return null;
            $result = "/" . $viz->s;
            if (isset($viz->p))
                $result .= "/" . $viz->p;
            return $result;
        }

        if (is_null($p))
        {
            return "/read/$s";
        }
        return "/read/$s/$p";
    }

    // link, s, p
    private static array $specialLinkMap = [
        [ "http://www.mspaintadventures.com/cascade.php?s=6&p=6009", "6", "006009" ],
        [ "http://www.mspaintadventures.com/DOTA/",                  "6", "006715" ],
        [ "http://www.mspaintadventures.com/007395/",                "6", "007395" ],
        [ "http://www.mspaintadventures.com/GAMEOVER/",              "6", "008801" ],
        [ "http://www.mspaintadventures.com/shes8ack/",              "6", "009305" ],
        [ "http://www.mspaintadventures.com/collide.html",           "6", "009987" ],
        [ "http://www.mspaintadventures.com/ACT7.html",              "6", "010027" ],
        [ "http://www.mspaintadventures.com/endcredits.html",        "6", "010030" ],
        // This is regularly just a full MSPA url in plaintext.
        // I could hardcode the chadthundercock domain or use just
        // "/oilretcon" but both of those aren't really good solutions.
        // Just make it into a hyperlink. Selecting, copying, and pasting
        // a link is kind of inconvenient for mobile devices anyway.
        [ "http://www.mspaintadventures.com/oilretcon.html", '<a href="/oilretcon" style="color: #eeeeee">/oilretcon</a>', ]
    ];

    // regex, replace
    private static array $miscRegexes = [
        [ '/"http:\/\/www\.mspaintadventures\.com\/storyfiles\/hs2\/waywardvagabond\/(.*?)\/"/m', "\"/waywardvagabond/$1\"" ],
        [ '/"http:\/\/www\.mspaintadventures\.com\/sweetbroandhellajeff\/\?cid=0*([0-9]+)\.(?:jpg|gif)"/', "\"/sbahj/$1\"" ],
        [ "/http:\/\/(www\.|cdn\.|)mspaintadventures\.com\/(?!oilretcon\.html|storyfiles\/hs2\/waywardvagabond)/", "/mspa/" ],
    ];

    private static function strSplice(string $str, int $offset, int $length, string $replacement): string
    {
        return substr($str, 0, $offset) . $replacement . substr($str, $offset + $length);
    }

    public static function replaceMspaLinks(string $html): string
    {
        foreach (self::$specialLinkMap as $mapping)
        {
            $text = $mapping[1];
            if (isset($mapping[2]))
            {
                $text = self::constructMspaLink($mapping[1], $mapping[2]);
            }
            $html = str_replace($mapping[0], $text, $html);
        }

        $html = preg_replace_callback(
            '/"(?:http:\/\/www\.mspaintadventures\.com\/(?:(?:index|scratch|cascade|trickster|ACT6ACT5ACT1x2COMBO|ACT6ACT6)\.php|)|)\?s=(.*?)(?:&|&amp;)p=(.*?)"/m',
            function(array $matches): string
            {
                return '"' . MSPALinks::constructMspaLink($matches[1], $matches[2]) . '"';
            },
            $html,
            flags: PREG_SET_ORDER
        );

        $html = preg_replace_callback(
            '/"(?:http:\/\/www\.mspaintadventures\.com\/(?:index\.php|)|)\?s=(.*?)"/m',
            function (array $matches): string
            {
                return '"' . MSPALinks::constructMspaLink($matches[1]) . '"';
            },
            $html,
            flags: PREG_SET_ORDER
        );

        foreach (self::$miscRegexes as $mapping)
        {
            $html = preg_replace($mapping[0], $mapping[1], $html);
        }

        return $html;
    }
}