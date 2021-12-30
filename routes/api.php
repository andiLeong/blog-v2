<?php

use App\Http\Controllers\PostController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware(['auth:sanctum','admin'])->group(function () {



	Route::post('/posts', [PostController::class,'store']);
	Route::delete('/posts/{post:slug}', [PostController::class,'destroy']);
	Route::patch('/posts/{post:slug}', [PostController::class,'update']);


});


	Route::get('/posts', [PostController::class,'index']);
	Route::get('/posts/{post:slug}', [PostController::class,'show']);

	

Route::get('/test', function () {



request()->session()->invalidate();

return 'doen';
$data = request()->session()->all();

dd($data);

	// auth()->loginUsingId(12);

    return 'hi';
});