<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});


Route::post('/login', \App\Http\Controllers\LoginController::class);
Route::post('/logout', \App\Http\Controllers\LogoutController::class);

Route::get('/validate', \App\Http\Controllers\ValidationController::class);


Route::get('/ip', function (App\Practice\Request\Request $request) {
    return $request->ip();
});
