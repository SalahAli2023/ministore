<?php
namespace MiniStore\Traits;

trait Discountable
{
    public function applyDiscount($price, $discountPercentage)
    {
        return $price * (1 - $discountPercentage);
    }
}