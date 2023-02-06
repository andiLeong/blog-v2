<?php

use App\Models\User;

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
    if($user = User::whereEmail('andiliang9988@gmail.com')->first()){
       return $user;
    }
    return create(User::class,['email' => 'andiliang9988@gmail.com']);
}
