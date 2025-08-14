<?php
namespace MiniStore\Modules\Users;

use MiniStore\Traits\Loggable;

class Admin extends User
{
    use Loggable;

    private $accessLevel;

    public function __construct($id, $name, $email, $password, $accessLevel = 1)
    {
        parent::__construct($id, $name, $email, $password);
        $this->accessLevel = $accessLevel;
        
        $this->logAction("Admin created: {$this->name}");
    }

    public function getRole() { return 'admin'; }

    public function getAccessLevel() { return $this->accessLevel; }

    public function setAccessLevel($level)
    {
        if (!in_array($level, [1, 2, 3])) {
            throw new \InvalidArgumentException("Invalid access level");
        }
        $this->accessLevel = $level;
        return $this;
    }
}