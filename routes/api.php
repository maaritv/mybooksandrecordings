<?php

use Illuminate\Http\Request;


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


use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\BookRestController;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('searchbook/{name}', [BookRestController::class, 'search_book']);


Route::resource('v1/bookdata', BookRestController::class);