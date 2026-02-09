<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/tools', function () {
    return view('tools.index');
})->name('tools.index');

Route::get('/tools/create', function () {
    return view('tools.create');
})->name('tools.create');
