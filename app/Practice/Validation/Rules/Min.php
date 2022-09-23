<?php

namespace App\Practice\Validation\Rules;

class Min extends Rule
{
    private $min;

    public function __construct($key, $value, $arguments)
    {
        parent::__construct($key, $value, $arguments);
        $this->min = $arguments[0];
    }

    public function check(): bool
    {
        return strlen($this->value) >= $this->min;
    }

    public function message() :string
    {
        return "The $this->key must at least be $this->min long";
    }


}
