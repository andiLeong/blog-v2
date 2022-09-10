<?php

namespace App\Practice\Observers;


class Foo implements Subject
{
    public $thing;

    public function handle()
    {
       $this->thing = get_class($this);
    }
}
