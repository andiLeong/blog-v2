<?php

namespace App\Practice\Observers\Subject;

use App\Practice\Observers\Observer\Observable;

trait Subjectable
{
    protected $observers = [];

    public function add($subject)
    {
        $subject = is_array($subject) ? $subject : func_get_args();

        foreach ($subject as $value) {
            if (!$value instanceof Observable) {
                throw new \Exception('We are expecting Subject instance, but the value passed is not');
            }
            $this->observers[] = $value;
        }
        return $this;
    }

    public function fire()
    {
        foreach ($this->observers as $observer) {
            $observer->handle($this);
        }
    }
}
