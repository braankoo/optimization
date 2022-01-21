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


Route::post('/login', 'App\Http\Controllers\LoginController@authenticate');
Route::middleware('auth:sanctum')->post('/logout', 'App\Http\Controllers\LoginController@logout');
Route::view('/{any}', 'index')->where('any', '(?!api|storage|app|public).*');
