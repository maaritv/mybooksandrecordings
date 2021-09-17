<?php

use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\BookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\RecordingController;


Route::redirect('/', 'books');

Route::get('/ohjeet', function () {    
    echo "Tässä ohjesivua!!";
   }
);

/*Route::get('user/profile', [
    'as' => 'profile', 'uses' => 'UserController@showProfile'
]);
*/

Route::put('lendings/returnbook/{id}', [App\Http\Controllers\LendingController::class, 'returnBook'])->name('returnbook');


Route::resource('books', BookController::class);
Route::resource('customers', CustomerController::class);
Route::resource('lendings', LendingController::class);
Route::resource('recordings', RecordingController::class);
Route::resource('css/app.css', 'public/css/app.css');