<?php
namespace MSPAToGo\Controller;

use MSPAToGo\Options;
use MSPAToGo\RequestMetadata;

class OptionsController extends PageController
{
    public string $template = "options";
    public string $title    = "Options";

    public function onGet(RequestMetadata $request): bool
    {
        $this->data->optionSchema = Options::getSchema();
        return true;
    }

    public function onPost(RequestMetadata $request): bool
    {
        Options::updateFromPost();
        return $this->onGet($request);
    }
}