<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SearchPostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

    Route::post('/files', [FileController::class,'store'])->middleware('file.morph.validation');
});


Route::get('/posts', [PostController::class,'index']);
Route::get('/posts/search', [SearchPostController::class,'index']);
Route::get('/posts/{post:slug}', [PostController::class,'show']);


Route::get('/tags', [TagController::class,'index']);

Route::get('/gallery/{gallery}', [GalleryController::class,'show'] );
Route::get('/video/{video}', [VideoController::class,'show'] );


Route::get('/order', [OrderController::class,'index']);
Route::delete('/order/{ids}', [OrderController::class,'destroy']);

Route::get('/weather', WeatherController::class);

Route::post('/location-distance', function(){

    $userLocation = request('target_longitude') - request('user_longitude');
    $storeLatitude = request('target_latitude');
    $dist =
        sin(deg2rad(  request('user_latitude')) )
        * sin(deg2rad($storeLatitude))
        +  cos(deg2rad(  request('user_latitude') ))
        * cos(deg2rad($storeLatitude))
        * cos(deg2rad($userLocation));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return round($miles * 1.609344,2);

//    return ($miles * 1.609344);

});


Route::post('/deploy', function(){

    $request = request();
    $signature = $request->headers->get('x-hub-signature-256');

    $hash = hash_hmac('sha256', $request->getContent(), 'foo-bar');
    if(! hash_equals(Str::after($signature, 'sha256='), $hash)){
        abort(404, 'The Page Is not existed');
    }

    return [
        'message' => 'receive github action',
        'request' => request()->all(),
        'header' => request()->header->all(),
    ];
});
