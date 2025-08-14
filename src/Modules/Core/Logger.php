<?php
namespace MiniStore\Modules\Core;

class Logger
{
    public static function log($message)
    {
        file_put_contents('logs.txt', date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
    }
}