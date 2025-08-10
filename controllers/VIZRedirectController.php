<?php
namespace MSPAToGo\Controller;

use MSPAToGo\RequestMetadata;

class VIZRedirectController extends PageController
{
    public function onGet(RequestMetadata $request): bool
    {
        $s = match ($request->path[0]) {
            "jailbreak"      => "1",
            "bard-quest"     => "2",
            "blood-spade"    => "3",
            "problem-sleuth" => "4",
            "beta"           => "5",
            "homestuck"      => "6",
            "ryanquest"      => "ryanquest",
        };

        $offset = match ($request->path[0]) {
            "jailbreak"      => 1,
            "bard-quest"     => 135,
            "blood-spade"    => null,
            "problem-sleuth" => 218,
            "beta"           => 1892,
            "homestuck"      => 1900,
            "ryanquest"      => 0,
        };

        switch (count($request->path))
        {
            case 1:
                header("Location: /read/$s");
                return true;
            case 2:
            {
                $vizP = $request->path[1];
                if ($s == "3")
                {
                    $p = "MC0001";
                }
                else if ($s == "1" && $vizP == "135")
                {
                    $p = "jb2_000000";
                }
                else if (intval($vizP) != $vizP)
                {
                    $p = $vizP;
                }
                else
                {
                    $p = str_pad(strval(intval($vizP) + $offset), 6, "0", STR_PAD_LEFT);
                }

                header("Location: /read/$s/$p");
                return true;
            }
            default:
                return false;
        }
    }
}