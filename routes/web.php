<?php
use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DiscountController::class, 'index']);
Route::post('/calculate', [DiscountController::class, 'calculate']);