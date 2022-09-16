<?php

namespace App\Practice\Observers\Observer;


use App\Practice\Observers\Subject\Subject;

class PublishedToTwitter implements Observable
{
    public function handle(Subject $subject)
    {
        return true;
    }
}
