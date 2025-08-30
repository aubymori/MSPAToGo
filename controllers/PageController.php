<?php
namespace MSPAToGo\Controller;

use MSPAToGo\ControllerManager;
use MSPAToGo\Options;
use MSPAToGo\RequestMetadata;

class PageController
{
    protected bool $usePageframe = true;
    protected string $template = "404";
    protected string $title = "";
    protected object $data;

    private static array $links =  [
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
            "text" => "NEWS",
            "url" => "/news",
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
            "url" => "/credits",
            "color" => "orange",
        ],
    ];

    private static array $homosuckLinkOverrides = [
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

    private function beforeController(): void
    {
        Options::loadFromCookies();
        $this->data = (object)[];
    }

    private function afterController(): void
    {
        $this->data->options = Options::getAll();
        if ($this->usePageframe)
        {
            if (!isset($this->data->theme))
            {
                $userTheme = Options::get("theme");
                $this->data->theme = ($userTheme == "default") ? null : $userTheme;
            }
            
            $this->data->links = self::$links;
            if ($this->data->theme == "homosuck")
            {
                foreach (self::$homosuckLinkOverrides as $i => $text)
                {
                    $this->data->links[$i]["text"] = $text;
                }
            }
        }

        if (!empty(trim($this->title)))
        {
            $this->data->title = trim($this->title);
        }

        ControllerManager::$twig->addGlobal("data", $this->data);
        echo ControllerManager::$twig->render($this->template . ".html", []);
        exit();
    }

    public function onGet(RequestMetadata $request): bool
    {
        return true;
    }

    public function get(RequestMetadata $request): void
    {
        $this->beforeController();

        if (!$this->onGet($request))
        {
            $this->template     = "404";
            $this->title        = "404 Not Found";
            $this->usePageframe = true;
            http_response_code(404);
        }

        $this->afterController();
    }

    public function post(RequestMetadata $request): void
    {
        $this->beforeController();

        if (!$this->onPost($request))
        {
            $this->template     = "404";
            $this->title        = "404 Not Found";
            $this->usePageframe = true;
            http_response_code(404);
        }

        $this->afterController();
    }

    public function onPost(RequestMetadata $request): bool
    {
        return $this->onGet($request);
    }
}