<?php
namespace MSPAToGo;

class Options
{
    // 400 days
    // https://developer.chrome.com/blog/cookie-max-age-expires
    private const COOKIE_EXPIRE_TIME = 34560000;
    
    private static array $options = [
        "desktop" => [
            "type"         => "checkbox",
            "name"         => "Desktop mode",
            "description"  => "Use a layout better suited for reading on a desktop computer.",
            "defaultValue" => false
        ],
        "viz-links" => [
            "type"         => "checkbox",
            "name"         => "Use Homestuck.com page numbers",
            "description"  => "Makes URLs use the Homestuck.com page numbers instead of the MSPA page numbers.",
            "defaultValue" => false
        ],
        "auto-open-logs" => [
            "type"         => "checkbox",
            "name"         => "Automatically open logs",
            "description"  => "Chat logs in Homestuck will start opened instead of collapsed.",
            "templateName" => "autologs",
            "defaultValue" => false
        ],
        "theme" => [
            "type"         => "dropdown",
            "name"         => "Page theme",
            "description"  => "A theme to use instead of the default one. This will not override page-specific themes.",
            "defaultValue" => "default",
            "options"      => [
                "default"   => "Default",
                "classic"   => "Classic",
                "dark"      => "Dark",
                "scratch"   => "Scratch",
                "sbahj"     => "SBAHJ",
                "cascade"   => "Cascade",
                "trickster" => "Trickster Mode",
                "homosuck"  => "HOMOSUCK",
                "collide"   => "Collide",
                "act7"      => "ACT 7"
            ]
        ],
        "font" => [
            "type"         => "dropdown",
            "name"         => "Page font",
            "description"  => "The font to use for page titles and text.",
            "defaultValue" => "courier",
            "options"      => [
                "courier"      => "Courier New",
                "opendyslexic" => "OpenDyslexic"
            ]
        ],
        "highcontrast" => [
            "type"         => "checkbox",
            "name"         => "High contrast text colors",
            "description"  => "Use high contrast colors for colored text, such as chat logs in Homestuck.",
            "defaultValue" => false,
        ]
    ];

    public static function loadFromCookies(): void
    {
        foreach (self::$options as $name => &$option)
        {
            if (isset($_COOKIE[$name]))
            {
                $value = $_COOKIE[$name];
                switch ($option["type"])
                {
                    case "checkbox":
                        $value = ($value == "true");
                        break;
                }
                
                $option["value"] = $value;
            }
        };
    }

    public static function updateFromPost(): void
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST")
            return;

        foreach (self::$options as $name => &$option)
        {
            if (isset($_POST[$name]))
            {
                $value = $_POST[$name];
                switch ($option["type"])
                {
                    case "checkbox":
                        $value = ($value == "on");
                        break;
                }
                self::set($name, $value);
            }
            // Unchecked checkboxes are not sent in POST requests
            else if ($option["type"] == "checkbox")
            {
                self::set($name, false);
            }
        }
    }

    public static function get(string $name): mixed
    {
        if (!isset(self::$options[$name]))
            return null;

        $option = self::$options[$name];
        return @$option["value"] ?? $option["defaultValue"];
    }

    public static function set(string $name, mixed $value): bool
    {
        if (!isset(self::$options[$name]))
            return false;

        $option = &self::$options[$name];
        switch ($option["type"])
        {
            case "checkbox":
                if (!is_bool($value))
                    return false;
                break;
            case "dropdown":
                if (!is_string($value))
                    return false;
                break;
        }

        $option["value"] = $value;

        $cookie_value = $value;
        switch ($option["type"])
        {
            case "checkbox":
                $cookie_value = $value ? "true" : "false";
                break;
        }
        setcookie($name, $cookie_value, time() + self::COOKIE_EXPIRE_TIME);
        return true;
    }

    public static function getAll(): object
    {
        $result = [];
        foreach (self::$options as $name => $option)
        {
            $outName = @$option["templateName"] ?? $name;
            $result[$outName] = self::get($name);
        }
        return (object)$result;
    }

    public static function getSchema(): array
    {
        return self::$options;
    }
}