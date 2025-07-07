<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Src\admin\user\application\GetUseryIdUseCase;
use Src\admin\user\infrastructure\repositories\EloquentUserRepository;

final class GetUserByIdGETController extends Controller
{

    public function index($id)
    {
        $eloquentUserRepository = new EloquentUserRepository();
        $getUserByIdUseCase = new GetUseryIdUseCase($eloquentUserRepository);

        $user = $getUserByIdUseCase($id);

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'message' => 'User retrieved successfully.'
        ]);
    }
}
