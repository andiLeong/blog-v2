<?php

namespace App\Practice\Validation\Rules;

class RequiredIf extends Rule
{

    public function check(): bool
    {
        $data = $this->getValue($this->arguments[0]);
        if(!is_null($data) && is_null($this->value)){
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return "The $this->key is required";
    }
}
