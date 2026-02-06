<?php

use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Tools Routes
Route::get('/tools', function () {
    return view('tools.index');
})->name('tools.index');

Route::get('/tools/create', function () {
    return view('tools.create');
})->name('tools.create');

// Sample auth routes untuk layout
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
