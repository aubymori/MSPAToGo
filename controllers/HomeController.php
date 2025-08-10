<?php
namespace MSPAToGo\Controller;

use MSPAToGo\RequestMetadata;

class HomeController extends PageController
{
    public string $template = "home";

    public function onGet(RequestMetadata $request): bool
    {
        // Load auto-save
        if (isset($_COOKIE["autosave"])
        && isset($_COOKIE["s_cookie"]) && isset($_COOKIE["p_cookie"]))
        {
            header("Location: /read/" . $_COOKIE["s_cookie"] . "/" . $_COOKIE["p_cookie"]);
            die();
        }
        return true;
    }
}