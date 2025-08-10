<?php
namespace MSPAToGo\Controller;

use MSPAToGo\MSPALinks;
use MSPAToGo\Network;
use MSPAToGo\RequestMetadata;

class SearchController extends PageController
{
    public string $template = "search";

    public function onGet(RequestMetadata $request): bool
    {
        if (count($request->path) > 2)
            return false;

        if (count($request->path) == 1)
            return true;

        $s = $request->path[1];
        $response = Network::mspaRequest("search/search_$s.txt");
        if ($response->status != 200)
            return false;

        $this->data->search_html = MSPALinks::replaceMspaLinks($response->body);

        return true;
    }
}