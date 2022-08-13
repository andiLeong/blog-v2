<?php

namespace App;

use Illuminate\Support\Traits\Conditionable;

class Foo
{
    use Conditionable;

    public $state = true;
    public $foo = 'bar';

    public function handle()
    {
        return $this->when($this->state, function ($foo) {
            return $foo->success();
        }, function ($foo) {
            return $foo->fail();
        });
    }

    public function success()
    {
        return ['success'];
    }

    public function fail()
    {
        return ['fail'];
    }

    public function higherOrderWhenProxy()
    {
       return $this->when($this->state)->foo;
    }
}
