<?php

namespace App\Practice\Observers\Observer;

use App\Practice\Observers\Subject\Subject;

class RecordUserLoginDetails implements Observable
{
    public $user;

    public function handle(Subject $subject)
    {
       $this->user = get_class($subject->user);
    }
}
