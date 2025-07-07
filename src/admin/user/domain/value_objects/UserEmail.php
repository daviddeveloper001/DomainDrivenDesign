<?php

namespace Src\admin\user\domain\value_objects;

class UserEmail
{
    private string $email;

    public function __construct(string $email)
    {
        if (empty($email)) {
            throw new \InvalidArgumentException("Email cannot be empty.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format.");
        }

        $this->email = $email;
    }

    public function value (): string
    {
        return $this->email;
    }
   
}