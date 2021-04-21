<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\General\MealController;
use App\Http\Controllers\General\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

Route::post('users', [UserController::class, 'store']);
Route::post('users/loginApp', [UserController::class, 'loginApp']);

Route::get('meals', [MealController::class, 'index']);
