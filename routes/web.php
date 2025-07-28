<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;


Route::controller(IndexController::class)->group(function () {
    Route::get('/', 'show')->name('show');
    Route::post('/upload', 'store')->name('upload');
});
