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

Route::get('/', function() {
    return response()->json(array('message'=>'Welcome to HN App'));
});

Route::get('last25', 'App\Http\Controllers\Controller@first25');

Route::get('last-week', 'App\Http\Controllers\Controller@last_week');

Route::get('last600-withkarma', 'App\Http\Controllers\Controller@last600');