<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\ProductsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/postProducts', [ProductsController::class, 'postProducts'])->name('postProducts');

Route::get('/getAllProducts', [ProductsController::class, 'getAllProducts'])->name('getAllProducts');
Route::get('/getAllCategories', [ProductsController::class, 'getAllCategories'])->name('getAllCategories');
