<?php

use Illuminate\Support\Facades\Route;
use Src\admin\user\infrastructure\controllers\GetUserByIdGETController;
use Src\admin\user\infrastructure\controllers\CreateUserPOSTController;

Route::post('/', [CreateUserPOSTController::class, 'index']);
Route::get('/{id}', [GetUserByIdGETController::class, 'index']);


