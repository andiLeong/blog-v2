<?php

namespace App\Practice\Validation\Rules;

class EndsWith extends Rule
{

    public function check(): bool
    {
        return str_ends_with($this->value,$this->arguments[0]);
    }

    public function message(): string
    {
        return "The $this->key must ends with {$this->arguments[0]}";
    }
}
