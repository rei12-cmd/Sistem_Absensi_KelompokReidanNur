<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::middleware(['auth'])->group(function() {
  Route::get('/', function () {
    return view('welcome');
  });
});
