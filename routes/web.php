<?php

use App\Http\Controllers\Client\CategoryController;
use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/{username}',[CategoryController::class,'index'])->name('categories.index');
Route::get('/{username}/{categorySlug}',[ProductController::class,'index'])->name('products.index');

Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/' . $path);

    return response()->file($file);
})->where('path', '.*');


Route::get('/{paths?}', function () {
    return view('admin.app');
})->where('paths', '.*');
