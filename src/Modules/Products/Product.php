<?php
namespace MiniStore\Modules\Products;

use MiniStore\Traits\Loggable;

class Product
{
    use Loggable;

    private $id;
    private $name;
    private $price;
    private $stock;
    private $description;

    public function __construct($id, $name, $price, $stock, $description = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->description = $description;
        
        $this->logAction("Product created: {$this->name}");
    }

    // Setters with validation
    public function setName($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Product name cannot be empty");
        }
        $this->name = $name;
        return $this;
    }

    public function setPrice($price)
    {
        if (!is_numeric($price) || $price <= 0) {
            throw new \InvalidArgumentException("Price must be a positive number");
        }
        $this->price = $price;
        return $this;
    }

    public function setStock($stock)
    {
        if (!is_int($stock) || $stock < 0) {
            throw new \InvalidArgumentException("Stock must be a non-negative integer");
        }
        $this->stock = $stock;
        return $this;
    }

    public function decreaseStock($quantity)
    {
        if ($quantity > $this->stock) {
            throw new \RuntimeException("Not enough stock available");
        }
        $this->stock -= $quantity;
        $this->logAction("Stock decreased for {$this->name} by $quantity");
        return $this;
    }

     // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getStock() { return $this->stock; }
    public function getDescription() { return $this->description; }

}