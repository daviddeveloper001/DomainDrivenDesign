<?php

namespace Src\admin\user\domain\value_objects;

class UserName
{
    private string $name;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("User name cannot be empty.");
        }
        if (strlen($name < 3 ))
        {
            throw new \InvalidArgumentException("User name must be at least 3 characters long.");
        }

        $this->name = $name;
    }

    public function value(): string
    {
        return $this->name;
    }
}