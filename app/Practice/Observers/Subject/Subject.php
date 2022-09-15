<?php

namespace App\Practice\Observers\Subject;

interface Subject
{
    public function add($subject);

    public function fire();
}
