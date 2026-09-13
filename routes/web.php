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

Route::get('/image-to-text', function () {
    return view('image_to_text');
});

Route::view('/text-to-image', 'text_to_image');
Route::get('/base64', function () {
    return view('base64');
})->name('base64');

Route::get('/age-calculator', function () {
    return view('age-calculator');
})->name('age-calculator');

Route::get('/hash-generator', function () {
    return view('hash-generator');
})->name('hash-generator');

Route::get('/color-palette', function () {
    return view('color-palette');
})->name('color-palette');

Route::get('/unit-converter', function () {
    return view('unit-converter');
})->name('unit-converter');

Route::get('/markdown-editor', function () {
    return view('markdown-editor');
})->name('markdown-editor');

Route::get('/ip-tools', function () {
    return view('ip-tools');
})->name('ip-tools');

Route::get('/date-calculator', function () {
    return view('date-calculator');
})->name('date-calculator');

Route::get('/email-validator', function () {
    return view('email-validator');
})->name('email-validator');

Route::get('/file-converter', function () {
    return view('file-converter');
})->name('file-converter');

Route::get('/url-encoder', function () {
    return view('url-encoder');
})->name('url-encoder');


Route::get('/mind-map', function () {
    return view('mind-map');
})->name('mind-map');

Route::get('/pdf-toolkit', function () {
    return view('pdf-toolkit');
})->name('pdf-toolkit');

Route::get('/memory-game', function () {
    return view('memory-game');
})->name('memory-game');

Route::get('/tractor-game', function () {
    return view('tractor-game');
})->name('tractor-game');

Route::get('/zipzap-game', function () {
    return view('zipzap-game');
})->name('zipzap-game');

Route::get('/highway-racer', function () {
    return view('highway-racer');
})->name('highway-racer');

// Route::get('/speed-test', function () {
//     return view('speed-test');
// })->name('speed-test');
// Route::get('/speed-checker', function () {
//     return view('speed-checker');
// })->name('speed-checker');

// Route for Bubble Shooter Game
Route::get('/bubble-shooter', function () {
    return view('bubble-shooter');
})->name('bubble.shooter');

Route::get('/bike-racer', function () {
    return view('bike-racer');
})->name('bike.racer');

Route::get('/arrow-maze', function () {
    return view('arrow-maze-game');
})->name('arrow-maze');