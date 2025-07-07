<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Src\admin\user\application\CreateUserUseCase;
use Src\admin\user\infrastructure\repositories\EloquentUserRepository;
use Src\admin\user\infrastructure\validators\CreateUserRequest;

final class CreateUserPOSTController extends Controller
{

    public function index(CreateUserRequest $request)
    {
        $eloquentUserRepository = new EloquentUserRepository();
        $createUserUseCase = new CreateUserUseCase($eloquentUserRepository);

        $createUserUseCase->execute(
            $request->input('id'),
            $request->input('name'),
            $request->input('email'),
            $request->input('password')
        );
    }
}
