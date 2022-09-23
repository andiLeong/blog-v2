<?php

namespace App\Practice\Validation\Rules;

class Required extends Rule
{
    public function check(): bool
    {
        return !is_null($this->value);
    }

    public function message() :string
    {
        return "The $this->key is required";
    }
}
