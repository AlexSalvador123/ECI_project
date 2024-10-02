<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PriceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('MainPage');
});

Route::get('/getPrice', [PriceController::class, 'getProductPrice']);
Route::post('/import', [PriceController::class, 'importPrices']);

require __DIR__.'/auth.php';
