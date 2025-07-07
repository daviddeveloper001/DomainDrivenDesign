<?php

use Illuminate\Support\Facades\Route;
use Src\admin\user\infrastructure\controllers\CreateUserPOSTController;
use Src\admin\user\infrastructure\controllers\GetUserByIdGETController;

Route::post('/store', [CreateUserPOSTController::class, 'index']);
Route::get('/{id}', [GetUserByIdGETController::class, 'index']);

Route::get('/', function () {
    return response()->json(['message' => 'User API is working']);
})->name('user.index');


