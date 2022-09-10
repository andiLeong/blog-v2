<?php

namespace App\Practice\Observers;

class Foo implements Observable
{
    public $thing;

    public function handle()
    {
       $this->thing = get_class($this);
    }
}
