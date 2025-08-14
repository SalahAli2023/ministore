<?php
namespace MiniStore\Modules\Core;

class Config
{
    private static $config;

    public static function load($configFile)
    {
        self::$config = require $configFile;
    }

    public static function get($key, $default = null)
    {
        return self::$config[$key] ?? $default;
    }
}