<?php

use App\Http\Controllers\General\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('orders', [OrderController::class, 'store']);
