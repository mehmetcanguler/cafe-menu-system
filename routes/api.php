<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


/** Auth */
Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/verify', [LoginController::class, 'verify']);
    Route::post('/send-code-to-email', [LoginController::class, 'sendCodetoEmailAgain']);
    Route::post('/send-code-to-phone', [LoginController::class, 'sendCodetoPhoneAgain']);
});
/** End Auth */

Route::middleware('auth:sanctum')->group(function () {
    
    /** Auth */
    Route::post('/auth/logout', [LogoutController::class, 'logout']);
    /** End Auth */

    /** Categories */
    Route::group(['prefix' => 'categories'], function () {
        Route::get('list', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'list']);
        Route::post('/{category}/images/multiple', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'storeImageMultiple']);
        Route::delete('/image/{file}', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'destroyImage']);

    });
    Route::apiResource('categories', \App\Http\Controllers\Api\Admin\CategoryController::class);
    /** End Categories */

    /** Products */
    Route::group(['prefix' => 'products'], function () {
        Route::post('/{product}/images/multiple', [\App\Http\Controllers\Api\Admin\ProductController::class, 'storeImageMultiple']);
        Route::delete('/image/{file}', [\App\Http\Controllers\Api\Admin\ProductController::class, 'destroyImage']);

    });
    Route::apiResource('products', \App\Http\Controllers\Api\Admin\ProductController::class);
    /** End Products */

});


