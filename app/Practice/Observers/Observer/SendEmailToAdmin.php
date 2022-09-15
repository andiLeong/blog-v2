<?php

namespace App\Practice\Observers\Observer;


use App\Practice\Observers\Subject\Subject;

class SendEmailToAdmin implements Observable
{
    public $user;

    public function handle(Subject $subject)
    {
       $this->user = get_class($subject->user);
    }
}
