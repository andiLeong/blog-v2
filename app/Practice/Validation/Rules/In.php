<?php

namespace App\Practice\Validation\Rules;

class In extends Rule
{
    private $in;

    public function __construct($key, $value, $arguments)
    {
        parent::__construct($key, $value, $arguments);
        $this->in = $arguments;
    }

    public function check(): bool
    {
        return in_array($this->value,$this->in);
    }

    public function message(): string
    {
        $in = implode(',',$this->in);
        return "The $this->key is not in $in";
    }
}
