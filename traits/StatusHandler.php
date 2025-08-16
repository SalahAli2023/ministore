<?php
namespace MiniStore\Traits;

trait StatusHandler
{
    
    public const STATUS_PENDING  = 'Pending';
    public const STATUS_PAID     = 'Paid';
    public const STATUS_FAILED   = 'Failed';
    public const STATUS_CANCELLED= 'Cancelled';
    public const STATUS_SHIPPED  = 'Shipped';
    
    protected $status = self::STATUS_PENDING;
    
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