<?php

namespace MiniStore\Modules\Orders;

use MiniStore\Modules\Products\Product;
use MiniStore\Modules\Users\Customer;
use MiniStore\Traits\Loggable;
use MiniStore\Traits\Discountable;
use MiniStore\Traits\StatusHandler;
use \MiniStore\Modules\Core\Config;

class Order
{
    use Loggable, Discountable, StatusHandler;

    private $id;
    private $customer;
    private $products = [];
    private $total;
    private $createdAt;

    public function __construct($id, Customer $customer)
    {
        $this->id = $id;
        $this->customer = $customer;
        $this->createdAt = new \DateTime();
        
        $this->logAction("Order created: $id");
    }

    public function addProduct(Product $product, $quantity = 1)
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be positive");
        }

        $this->products[] = [
            'product' => $product,
            'quantity' => $quantity
        ];

        $this->logAction("Product {$product->getName()} added to order {$this->id}");
        // return $this;
    }

    public function calculateBeforeTaxDiscount()
    {
        $subtotal = 0.0;
        foreach ($this->products as $item) {
            $subtotal += $item['product']->getPrice() * $item['quantity'];
        }
        return round($subtotal, 2);
    }

    public function calculateTotal($applyDiscount = false)
    {
        $subtotal = $this->calculateBeforeTaxDiscount();
        $taxRate = Config::get('tax_rate');
        $taxAmount = $subtotal * $taxRate;

        $this->total = $subtotal + $taxAmount;

        if ($applyDiscount) {
            $discountPercentage = Config::get('discount_percentage');
            $this->total = $this->applyDiscount($this->total, $discountPercentage);
            $this->logAction("Discount applied to order {$this->id}");
        }

        return $this->total;
    }

    public function returnCalculateTotal($applyDiscount = false)
    {
        $subtotal = $this->calculateBeforeTaxDiscount();
        $taxRate = Config::get('tax_rate');

        if ($applyDiscount) {
            $discountPercentage = Config::get('discount_percentage');
            $discount=$subtotal*$discountPercentage;
            $totalAfterDiscount = $this->applyDiscount($subtotal, $discountPercentage);
            $this->logAction("Discount applied to order {$this->id}");
        }
        $taxAmount = $totalAfterDiscount * $taxRate;
        $this->total = $totalAfterDiscount + $taxAmount;
        return [
            'totalBeforeTaxDiscount'=>$subtotal,
            'discount_percent' => $discountPercentage,
            'discount_value' => $discount,
            'totalAfterDiscount'=>$totalAfterDiscount,
            'tax_rate' => $taxRate,
            'tax' => $taxAmount,
            'total' => $this->total,
        ]
        ;
    }

    public function getProducts() { return $this->products; }
    public function getCustomer() { return $this->customer; }
    public function getTotal() { return $this->total; }
    public function getId() { return $this->id; }
    public function getCreatedAt() { return $this->createdAt; }
}