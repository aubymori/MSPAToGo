<?php
namespace MSPAToGo\Controller;

use MSPAToGo\RequestMetadata;

class SBAHJController extends PageController
{
    public string $template = "sbahj";
    public bool $usePageframe = false;

    private const FIRST_COMIC = 1;
    private const LAST_COMIC  = 54;

    private static array $gifComics = [ 21, 29, 34, 49 ];

    public function onGet(RequestMetadata $request): bool
    {
        $comic = isset($request->path[1]) ? intval($request->path[1]) : self::FIRST_COMIC;
        if (count($request->path) > 2
        || $comic < self::FIRST_COMIC || $comic > self::LAST_COMIC
        || $comic == 39) // There is a gap in SBAHJ numbers.
        {
            return false;
        }

        $this->data->first = self::FIRST_COMIC;
        $this->data->prev = max(self::FIRST_COMIC, $comic - 1);
        $this->data->next = min(self::LAST_COMIC, $comic + 1);
        $this->data->last = self::LAST_COMIC;

        // Aforementioned gap
        if ($this->data->next == 39)
            $this->data->next = 40;
        if ($this->data->prev == 39)
            $this->data->prev = 38;

        $ext = ".jpg";
        if (in_array($comic, self::$gifComics))
            $ext = ".gif";

        // Twig doesn't have a filter for this, this is for convenience
        $this->data->img = str_pad($comic, 3, "0", STR_PAD_LEFT) . $ext;
        
        return true;
    }
}