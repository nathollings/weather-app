<?php

use App\Models\Data;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'weatherData' => Data::getData()->Data(),
        'dataOptions' => Data::getData()->Options(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/charts', function () {
    return Inertia::render('Charts', [
        'weatherData' => Data::getData()->Data(),
        'dataOptions' => Data::getData()->Options(),
    ]);
})->middleware(['auth', 'verified'])->name('charts');


// demo data;
Route::get('/data', function() {
    return response()->json(Data::getData()->Data());
});
// demo data;
Route::get('/options', function() {
    return response()->json(Data::getData()->Options());
});

require __DIR__.'/auth.php';
