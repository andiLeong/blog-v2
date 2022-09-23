<?php

namespace App\Practice\Validation\Rules;

class Email extends Rule
{
    public function check(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_EMAIL);
    }

    public function message() :string
    {
        return "The $this->key must be a valid email";
    }

}
