<?php

namespace App\Practice\Observers;


class Observer
{
    private $observers = [];

    public function observe($subject)
    {
        if (is_array($subject)) {
            return $this->observers = $subject;
        }
        return $this->observers = func_get_args();
    }

    public function fire()
    {
        foreach ($this->observers as $observer) {
            $observer->handle();
        }
    }
}
