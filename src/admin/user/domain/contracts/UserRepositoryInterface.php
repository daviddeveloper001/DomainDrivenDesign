<?php

namespace Src\admin\user\domain\contracts;

use Src\admin\user\domain\entities\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ? User;

    public function save(User $user): void;

}