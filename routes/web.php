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

// Route::get('/', function () {
//     return view('welcome');
// });


use App\Http\Controllers\CompareController;
use App\Http\Controllers\QrCodeController;

Route::get('/', [CompareController::class,'index']);
Route::post('/compare',[CompareController::class,'compare'])->name('compare');

Route::get('/json_formatter', function () {
    return view('json_formatter');
});
// Password Generator tool
Route::get('/password-generator', function () {
    return view('password_generator');
});

// sql minifier

Route::get('/sql_minifier', function () {
    return view('sql_minifier');
});

Route::get('/dashboard', function () {
    return view('dashboard'); // 👈 DASHBOARD
});

Route::get('/code-beautifier', function () {
    return view('code_beautifier');
});

// API TESTER ROUTE
Route::get('/api_tester', function () {
    return view('api_tester');
});

// QR CODE GENERATOR ROUTE
Route::get('/qr-generator', [QrCodeController::class, 'index'])->name('qr.generator');
Route::post('/qr-generator/create', [QrCodeController::class, 'generate'])->name('qr.generator.create');