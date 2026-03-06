<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;


Route::get('/', function () {
    return view('home');
});

Route::get('/hello', function () {
    return 'Hello World';
});


Route::prefix('products')->group(function () {
    Route::get('/food', function () {
        return view('products.food');
    });

    Route::get('/beauty', function () {
        return view('products.beauty');
    });

    Route::get('/homecare', function () {
        return view('products.homecare');
    });

    Route::get('/baby', function () {
        return view('products.baby');
    });
});


Route::get('/products/user/{id}/name/{name}', function ($id, $name) {
    return view('products.user', compact('id', 'name'));
});


Route::get('/products/sales', function () {
    return view('products.sales');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);