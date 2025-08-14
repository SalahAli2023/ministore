<?php
namespace MiniStore\Traits;

trait StatusHandler
{
    protected $status = 'pending';

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
}