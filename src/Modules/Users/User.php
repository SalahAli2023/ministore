<?php
namespace MiniStore\Modules\Users;

abstract class User
{
    protected $id;
    protected $name;
    protected $email;
    protected $password;

    public function __construct($id, $name, $email, $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    abstract public function getRole();

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }

    // Setters with validation
    public function setName($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Name cannot be empty");
        }
        $this->name = $name;
        return $this;
    }

    public function setEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }
        $this->email = $email;
        return $this;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}