<?php

namespace Src\admin\user\domain\entities;

use Src\admin\user\domain\value_objects\UserEmail;
use Src\admin\user\domain\value_objects\UserName;
use Src\admin\user\domain\value_objects\UserPassword;

class User
{
    private int $id;
    private UserName $name;
    private UserEmail $email;
    private UserPassword $password;

    public function __construct(int $id, UserName $name, UserEmail $email, UserPassword $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }
}
