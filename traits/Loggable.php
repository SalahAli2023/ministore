<?php
namespace MiniStore\Traits;

trait Loggable
{
    public function logAction($action)
    {
        $className = get_class($this);
        $message = "[$className] $action";
        \MiniStore\Modules\Core\Logger::log($message);
    }
}