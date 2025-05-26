<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/user/register', [UserController::class, 'register'])->name('api.register');
Route::post('/user/login', [UserController::class, 'login'])->name('api.login');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user/profile', [UserController::class, 'profile'])->name('api.profile');
    Route::post('/user/logout', [UserController::class, 'logout'])->name('api.logout');
    Route::put('/user/profile', [UserController::class, 'updateProfile'])->name('api.profile.update');


});
    // Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
        Route::get('/category/{category}', [ProductController::class, 'getByCategory']);
        Route::get('/category-tree/{category}', [ProductController::class, 'getByCategoryTree']);
        Route::get('/parent-category/{category}', [ProductController::class, 'getByParentCategory']);
    });

    // Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
        Route::get('/parent/show', [CategoryController::class, 'showByParent']);
        Route::get('/tree', [CategoryController::class, 'getCategoryTree']);
        Route::post('/reorder', [CategoryController::class, 'reorder']);
        Route::get('/{category}/children', [CategoryController::class, 'showCategoryWithChildren']);
        Route::get('/{category}/products', [CategoryController::class, 'getProducts']);
    });

    // Order Routes
    Route::apiResource('orders', OrderController::class);
