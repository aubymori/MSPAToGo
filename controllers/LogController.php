<?php
namespace MSPAToGo\Controller;

use MSPAToGo\MSPALinks;
use MSPAToGo\Network;
use MSPAToGo\RequestMetadata;

class LogController extends PageController
{
    public string $template = "log";

    public function onGet(RequestMetadata $request): bool
    {
        if (count($request->path) > 3)
            return false;

        if (count($request->path) == 1)
        {
            $this->data->adventures = json_decode(file_get_contents("static/adventures.json"));
            return true;
        }

        $reverse = false;
        if (isset($request->path[2]))
        {
            if (strtolower($request->path[2]) != "rev")
                return false;
            $reverse = true;
        }

        $s = $request->path[1];
        
        $response = Network::mspaRequest("logs/" . ($reverse ? "log_rev_" : "log_") . "$s.txt", true);
        if ($response->status != 200)
            return false;

        $this->data->reversed = $reverse;
        $this->data->log_html = MSPALinks::replaceMspaLinks($response->body);
        $this->data->s = $s;

        return true;
    }
}