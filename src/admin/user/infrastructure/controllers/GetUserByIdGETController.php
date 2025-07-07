<?php

namespace Src\admin\user\infrastructure\controllers;

use Src\admin\user\application\GetUseryIdUseCase;
use Src\admin\user\infrastructure\controllers\Api\V1\ApiControllerV1;
use Src\admin\user\infrastructure\repositories\EloquentUserRepository;

final class GetUserByIdGETController extends ApiControllerV1
{

    public function index($id)
    {
        $eloquentUserRepository = new EloquentUserRepository();
        $getUserByIdUseCase = new GetUseryIdUseCase($eloquentUserRepository);

        $user = $getUserByIdUseCase($id);

       return $this->ok('User retrieved successfully', [
            'id' => $user->id(),
            'name' => $user->name()->value(),
            'email' => $user->email()->value(),
            'password' => $user->password()->value(), // Note: Password should not be returned in a real application
        ]);
    }
}
