<?php

use App\Models\User;
use Illuminate\Support\Facades\Config;

function create($class, $attributes = [], $times = null)
{
    return $class::factory($times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return $class::factory($times)->make($attributes);
}

function admin()
{
    $adminEmail = 'admin@gmail.com';
    Config::set('app.admin', explode(',', $adminEmail));
    if($user = User::whereEmail($adminEmail)->first()){
       return $user;
    }
    return create(User::class,['email' => $adminEmail]);
}
