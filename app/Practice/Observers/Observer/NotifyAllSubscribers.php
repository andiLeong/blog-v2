<?php

namespace App\Practice\Observers\Observer;


use App\Practice\Observers\Subject\Subject;

class NotifyAllSubscribers implements Observable
{
    public $post;

    public function handle(Subject $subject)
    {
        $this->post = get_class($subject->post);
    }
}
