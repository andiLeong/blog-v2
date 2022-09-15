<?php

namespace App\Practice\Observers\Observer;

use App\Practice\Observers\Subject\Subject;

interface Observable
{
    public function handle(Subject $subject);
}
