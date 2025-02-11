<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PartController;

Route::resource('cars', CarController::class);
Route::resource('parts', PartController::class);
Route::get('/', function () {
    return view('app');
});
