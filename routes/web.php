<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('portal', function() {
    return view('map');
})->name('home');

Route::get('contact', function() {
    return view('map');
})->name('contact');

Route::get('terms', function() {
    return view('map');
})->name('terms');

Route::get('about', function() {
    return view('map');
})->name('about');
