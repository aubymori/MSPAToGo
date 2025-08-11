<?php
namespace MSPAToGo;

class ServerConfig
{
    private static ?object $config = null;

    public static function __initStatic()
    {   
        $has_adventure = new \Twig\TwigFunction("has_adventure", function(string $s): bool
        {
            return ServerConfig::hasAdventure($s);
        });
        ControllerManager::$twig->addFunction($has_adventure);

        $is_offline_mode = new \Twig\TwigFunction("is_offline_mode", function(): bool
        {
            return ServerConfig::isOfflineMode();
        });
        ControllerManager::$twig->addFunction($is_offline_mode);
    }

    public static function ensure(): void
    {
        if (!is_null(self::$config))
            return;

        self::$config = @json_decode(file_get_contents("config.json")) ?? null;
        if (is_null(self::$config))
        {
            self::$config = (object)[
                "offline_mode" => false
            ];
        }
    }

    public static function isOfflineMode(): bool
    {
        self::ensure();
        return @self::$config->offline_mode ?? false;
    }

    public static function hasAdventure(string $s): bool
    {
        if (!self::isOfflineMode())
            return true;
        return is_dir("mspa_local/$s");
    }
}