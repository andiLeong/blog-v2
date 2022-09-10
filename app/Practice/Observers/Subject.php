<?php

namespace App\Practice\Observers;

interface Subject
{
    public function observe($subject);

    public function fire();
}
