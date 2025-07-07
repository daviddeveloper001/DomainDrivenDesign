<?php

namespace Src\admin\user\application;

use Src\admin\user\domain\contracts\UserRepositoryInterface;
use Src\admin\user\domain\value_objects\UserName;
use Src\admin\user\domain\value_objects\UserEmail;
use Src\admin\user\domain\value_objects\UserPassword;
use Src\admin\user\domain\entities\User;

class CreateUserUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $id, string $name, string $email, string $password): void
    {
        $nameValueObject = new UserName($name);
        $emailValueObject = new UserEmail($email);
        $passwordValueObject = new UserPassword($password);

        $user = new User($id, $nameValueObject, $emailValueObject, $passwordValueObject);

        // Save the user using the repository
        $this->userRepository->save($user);
    }
}
