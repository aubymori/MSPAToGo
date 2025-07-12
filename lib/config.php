<?php

class Config
{
    private static ?object $config = null;

    private static function ensure(): void
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
}