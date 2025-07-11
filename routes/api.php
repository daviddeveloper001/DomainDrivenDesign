<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('admin/user')->group(base_path('src/admin/user/infrastructure/routes/api.php'));

Route::prefix('Catalog_Product')->group(base_path('src/Catalog/Product/infrastructure/routes/api.php'));
