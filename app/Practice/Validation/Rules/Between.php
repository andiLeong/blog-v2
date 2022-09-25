<?php

namespace App\Practice\Validation\Rules;

class Between extends Rule
{
    public function check(): bool
    {
        return $this->value >= $this->arguments[0] && $this->value <= $this->arguments[1];
    }

    public function message(): string
    {
        $range = implode(',',
            array_slice($this->arguments,0,2)
        );
        return "The $this->key must between $range";
    }
}
