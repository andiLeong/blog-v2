<?php

namespace App\Practice\Validation\Rules;


class RequiredIf extends Rule
{

    public function check(): bool
    {
        if(!is_null($this->request->get($this->arguments[0])) && is_null($this->value)){
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return "The $this->key is required";
    }
}
