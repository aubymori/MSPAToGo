<?php
namespace MSPAToGo;

use MSPAToGo\GlobToRegexp;
use MSPAToGo\RequestMetadata;

class ControllerManager
{
    /**
     * Router definitions
     */
    private static array $routes = [];

    private static \Twig\Loader\FilesystemLoader $twigLoader;

    public static \Twig\Environment $twig;

    /**
     * Redirect definitions
     */
    private static array $redirects = [];

    public static function __initStatic()
    {
        self::$twigLoader = new \Twig\Loader\FilesystemLoader("templates");
        self::$twig = new \Twig\Environment(self::$twigLoader, []);
    }

    /**
     * Add definitons for the router.
     * 
     * URLs are in a glob/Unix style pattern.
     */
    public static function route(array $routes): void
    {
        self::$routes += $routes;
    }

    /**
     * Add redirect definitions.
     * 
     * When you use a function, the user will be
     * redirect to the returned value of that
     * function. Functions also get passed a
     * RequestMetadata option as the only parameter.
     */
    public static function redirect(array $redirects): void
    {
        self::$redirects += $redirects;
    }

    public static function run(): void
    {   
        // Check redirects and do that
        // if applicable
        foreach(self::$redirects as $from => $to)
        {
            // Make current definition into regexp
            // to compare
            $regexp = GlobToRegexp::convert($from, $_SERVER["REQUEST_URI"]);

            if (preg_match($regexp, $_SERVER["REQUEST_URI"]))
            {
                $url = null;

                if (is_callable($to))
                {
                    $url = $to(new RequestMetadata());

                    // Prevent unexpected behavior
                    if (!is_string($url)) return;
                }
                else
                {
                    // Quick and easy behavior
                    $url = preg_replace($regexp, $to, $_SERVER["REQUEST_URI"]);
                }

                // Finally, redirect
                header("Location: {$url}");
                die();
            }
        }

        
        $match = null;
        if (isset(self::$routes))
        {
            foreach(self::$routes as $url => $cpath)
            {
                if (\fnmatch($url, explode("?", $_SERVER["REQUEST_URI"])[0]))
                {
                    $match = $cpath;
                    break;
                }
            }

            $method = strtolower($_SERVER["REQUEST_METHOD"]);
            if (!is_null($match))
            {
                self::invokeHandlerForMatch($match, $method);
            }
            else if (isset(self::$routes["default"]))
            {
                self::invokeHandlerForMatch(self::$routes["default"], $method);
            }
            else
            {
                throw new \Exception(
                    "No matching controllers or default definition"
                );
            }
        }
    }

    /**
     * Invokes the controller handler for a match to a class.
     * 
     * @param string $pointer
     * @param string $method that now finally gets used by the module!
     * @return mixed|void
     */
    protected static function invokeHandlerForMatch(
        string $pointer, 
        string $method
    )
    {
        // String pointers are class name references, so we will query for information
        // about this class.
        if (!class_exists($pointer))
        {
            throw new \Exception("Controller class does not exist: " . $pointer);
        }

        $instance = new $pointer();
        $request = new RequestMetadata;

        if (method_exists($instance, $method))
            return $instance->{$method}($request);
        else
            return $instance->get($request);

        exit();
    }
}