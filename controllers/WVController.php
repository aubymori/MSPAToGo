<?php
namespace MSPAToGo\Controller;

use MSPAToGo\RequestMetadata;

class WVController extends PageController
{
    public string $template = "waywardvagabond";
    public bool $usePageframe = false;

    private static array $imageCounts = [
        "anagitatedfinger"       => 4,
        "anunsealedtunnel"       => 7,
        "asentrywakens"          => 5,
        "astudiouseye"           => 6,
        "beneaththegleam"        => 2,
        "preparesforcompany"     => 1,
        "recordsastutteringstep" => 6,
        "windsdownsideways"      => 7,
    ];

    public function onGet(RequestMetadata $request): bool
    {
        if (count($request->path) != 2)
            return false;

        $id = strtolower($request->path[1]);
        if (!isset(self::$imageCounts[$id]))
            return false;

        $this->data->id = $id;
        $this->data->image_count = self::$imageCounts[$id];

        return true;
    }
}