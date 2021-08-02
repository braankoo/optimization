<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/adPlatforms', [ \App\Http\Controllers\AdPlatformController::class, 'index' ]);
Route::get('/adPlatforms/{adPlatform}/chart', [ \App\Http\Controllers\AdPlatformController::class, 'chartSingle' ]);


Route::get('/{adPlatform}/client', [ \App\Http\Controllers\ClientController::class, 'index' ]);
Route::get('/{adPlatform}/client/chart', [ \App\Http\Controllers\ClientController::class, 'chartIndex' ]);

Route::get('/{adPlatform}/client/{client}/chart', [ \App\Http\Controllers\ClientController::class, 'chartSingle' ]);
Route::get('/{adPlatform}/client/{client}', [ \App\Http\Controllers\ClientController::class, 'show' ]);


Route::get('/{adPlatform}/client/{client}/campaign/{campaign}/chart', [ \App\Http\Controllers\CampaignController::class, 'chartIndex' ]);
Route::get('/{adPlatform}/client/{client}/campaign/{campaign}', [ \App\Http\Controllers\CampaignController::class, 'show' ]);


