<?php



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use go280286sai\laravel_openssl\Http\Controllers\OpenSslController;
use Illuminate\Support\Facades\Route;

Route::post('/api.message', [OpenSslController::class, 'index']);
