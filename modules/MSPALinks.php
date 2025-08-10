<?php
namespace MSPAToGo;

class MSPALinks
{
    // link, s, p
    private static array $specialLinkMap = [
        [ "http://www.mspaintadventures.com/cascade.php?s=6&p=6009", "6", "006009" ],
        [ "http://www.mspaintadventures.com/DOTA/".                  "6", "006715" ],
        [ "http://www.mspaintadventures.com/007395/".                "6", "007395" ],
        [ "http://www.mspaintadventures.com/GAMEOVER/".              "6", "008801" ],
        [ "http://www.mspaintadventures.com/shes8ack/".              "6", "009305" ],
        [ "http://www.mspaintadventures.com/collide.html".           "6", "009987" ],
        [ "http://www.mspaintadventures.com/ACT7.html".              "6", "010027" ],
        [ "http://www.mspaintadventures.com/endcredits.html".        "6", "010030" ],
        // This is regularly just a full MSPA url in plaintext.
        // I could hardcode the chadthundercock domain or use just
        // "/oilretcon" but both of those aren't really good solutions.
        // Just make it into a hyperlink. Selecting, copying, and pasting
        // a link is kind of inconvenient for mobile devices anyway.
        [ "http://www.mspaintadventures.com/oilretcon.html", '<a href="/oilretcon" style="color: #eeeeee">/oilretcon</a>', ]
    ];

    // regex, replace
    private static array $regexes = [
        [ '/"http:\/\/www\.mspaintadventures\.com\/storyfiles\/hs2\/waywardvagabond\/(.*?)\/"/m', "\"/waywardvagabond/$1\"" ],
        [ '/"http:\/\/www\.mspaintadventures\.com\/sweetbroandhellajeff\/\?cid=0*([0-9]+)\.(?:jpg|gif)"/', "\"/sbahj/$1\"" ],
        [
            '/"(?:http:\/\/www\.mspaintadventures\.com\/(?:(?:index|scratch|cascade|trickster|ACT6ACT5ACT1x2COMBO|ACT6ACT6)\.php|)|)\?s=(.*?)(?:&|&amp;)p=(.*?)"/m',
            "/read/$1/$2"
        ],
        [ '/"(?:http:\/\/www\.mspaintadventures\.com\/(?:index\.php|)|)\?s=(.*?)"/m', "/read/$1" ],
        [ "/http:\/\/(www\.|cdn\.|)mspaintadventures\.com\/(?!oilretcon\.html|storyfiles\/hs2\/waywardvagabond|sweetbroandhellajeff)/", "/mspa/" ],
    ];

    public static function replaceMspaLinks(string $html): string
    {
        foreach (self::$specialLinkMap as $mapping)
        {
            $html = str_replace($mapping[0], $mapping[1], $html);
        }
        
        foreach (self::$regexes as $mapping)
        {
            $html = preg_replace($mapping[0], $mapping[1], $html);
        }

        return $html;
    }
}