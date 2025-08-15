<?php
namespace MiniStore\Modules\Core;

class Config
{
    private static $config;

    public static function load($configFile)
    {
        if (!file_exists($configFile)) {
            throw new RuntimeException("Config file not found");
        }
        self::$config = require $configFile;
    }

    public static function get($key, $default = null)
    {
        return self::$config[$key] ?? $default;
    }
}