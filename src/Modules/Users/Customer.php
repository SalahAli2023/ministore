<?php
namespace MiniStore\Modules\Users;

use MiniStore\Traits\Loggable;

class Customer extends User
{
    use Loggable;

    private $address;
    private $phone;

    public function __construct($id, $name, $email, $password, $address = '', $phone = '')
    {
        parent::__construct($id, $name, $email, $password);
        $this->address = $address;
        $this->phone = $phone;
        
        $this->logAction("Customer created: {$this->name}");
    }

    public function getRole() { return 'customer'; }

    // Additional getters/setters
    public function getAddress() { return $this->address; }
    public function getPhone() { return $this->phone; }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function setPhone($phone)
    {
        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            throw new \InvalidArgumentException("Invalid phone number format");
        }
        $this->phone = $phone;
        return $this;
    }
}