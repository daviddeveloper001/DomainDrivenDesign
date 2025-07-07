<?php

namespace Src\admin\user\domain\value_objects;

class UserPassword
{
    private string $password;

    public function __construct(string $password)
    {
        if (empty($password)) {
            throw new \InvalidArgumentException("Password cannot be empty.");
        }
        if (strlen($password) < 6) {
            throw new \InvalidArgumentException("Password must be at least 6 characters long.");
        }

        $this->password = $password;
    }

    public function value(): string 
    {
        return $this->password;
    }
}