<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::prefix("v1")->group(function (){
        Route::prefix("sales")->group(function (){
            Route::get("/");
            Route::post("/");
            Route::put("/");
            Route::delete("/");
        });
    });
});
