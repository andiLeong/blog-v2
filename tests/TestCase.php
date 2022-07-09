<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn(User $user = null, Array $attributes = null)
    {
        $user ??= create(User::class,$attributes ??= []);
        $this->actingAs($user);
        return $this;
    }

    public function admin()
    {
        $user = create(User::class,['email' => 'andiliang9988@gmail.com']);
        return $this->signIn($user);
    }
}
