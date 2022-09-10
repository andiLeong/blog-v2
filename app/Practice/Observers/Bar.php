<?php

namespace App\Practice\Observers;


class Bar implements Observable
{
    public $thing;

    public function handle()
    {
       $this->thing = get_class($this);
    }
}
