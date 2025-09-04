<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ImageController;

// Images dowload
Route::get('/images/{id}/download', [ImageController::class, 'download'])->name('images.download');
