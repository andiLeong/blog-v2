<?php

use App\Foo;
use App\Models\Order;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    $pdo = new \PDO("mysql:host=localhost;dbname=$database", $username, $password);
    $mysqlConnection = new MySqlConnection($pdo);

    $queryBuilder = new Builder($mysqlConnection);

    $queryBuilder = $queryBuilder
//        ->select(['id','country','id'])
        ->from('orders')
//        ->where('id','>',3)
            ->whereBetween('amount',[0,9999])
        ->whereIn('acount',['foo','bar'])

//        ->toSql()
//        ->get(['id','country'])
    ;

    dd($queryBuilder);
    return view('welcome');
});




Route::post('/login', \App\Http\Controllers\LoginController::class);
Route::post('/logout', \App\Http\Controllers\LogoutController::class);




