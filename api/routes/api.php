<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Sales\SalesController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth.jwt'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::prefix("v1")->group(function (){
        Route::prefix("sales")->group(function (){
            Route::get("/", [SalesController::class, 'index']);
            Route::get("/ex/{external_id}", [SalesController::class, 'show']);
            Route::post("/", [SalesController::class, 'newSale']);
            Route::put("/");
            Route::delete("/", [SalesController::class, 'delete']);
        });

        Route::prefix("sellers")->group(function (){
            Route::get("/my-sales", [SalesController::class, 'mySales']);
            Route::get("/email/notify/{seller_id}", [SalesController::class, 'sendEmailNotification']);
            Route::get("/", [\App\Http\Controllers\Users\UserController::class, 'listSellers']);
            Route::post("/", [\App\Http\Controllers\Users\UserController::class, 'createSeller']);
            Route::put("/{id}", [\App\Http\Controllers\Users\UserController::class, 'updateSeller']);
            Route::delete("/{id}", [\App\Http\Controllers\Users\UserController::class, 'deleteSeller']);
        });
    });
});
