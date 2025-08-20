<?php
namespace MSPAToGo\Controller;

use MSPAToGo\MSPALinks;
use MSPAToGo\Network;
use MSPAToGo\RequestMetadata;

class MapController extends PageController
{
    public string $template = "map";

    public function onGet(RequestMetadata $request): bool
    {
        if (count($request->path) > 2)
            return false;

        if (count($request->path) == 1)
        {
            $this->data->adventures = json_decode(file_get_contents("static/adventures.json"));
            $this->title = "Select Map";
            return true;
        }

        $s = $request->path[1];
        $response = Network::mspaRequest("maps/$s.html", true);
        if ($response->status != 200)
            return false;
        $this->data->map_html = MSPALinks::replaceMspaLinks($response->body);
        
        $this->title = "Adventure Map";

        return true;
    }
}