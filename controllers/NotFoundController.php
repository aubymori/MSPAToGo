<?php
namespace MSPAToGo\Controller;

use MSPAToGo\RequestMetadata;

class NotFoundController extends PageController
{
    public function onGet(RequestMetadata $request): bool
    {
        return false;
    }
}