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

// auth
Route::prefix('auth')->group(function() {
    Route::post('/login', 'AuthController@login');
    Route::post('/signup', 'AuthController@signup');
});

// Socio
Route::prefix('/socios')->middleware('auth:api')->group(function () {
    Route::get('/', 'SocioController@search');
    Route::post('/create', 'SocioController@create');
    Route::put('/update/{id}', 'SocioController@update');
    Route::get('/show/{id}', 'SocioController@show');
    Route::delete('/delete/{id}', 'SocioController@delete');
    Route::delete('/restore/{id}', 'SocioController@restore');
});

// Socios
Route::prefix('/socios')->middleware('auth:api')->group(function () {
    Route::get('/', 'SocioController@search');
    Route::post('/create', 'SocioController@create');
    Route::put('/update/{id}', 'SocioController@update');
    Route::get('/show/{id}', 'SocioController@show');
    Route::delete('/delete/{id}', 'SocioController@delete');
    Route::delete('/restore/{id}', 'SocioController@restore');
});

// Operarios
Route::prefix('/operarios')->middleware('auth:api')->group(function () {
    Route::get('/', 'OperarioController@search');
    Route::post('/create', 'OperarioController@create');
    Route::put('/update/{id}', 'OperarioController@update');
    Route::get('/show/{id}', 'OperarioController@show');
    Route::delete('/delete/{id}', 'OperarioController@delete');
    Route::delete('/restore/{id}', 'OperarioController@restore');
});


