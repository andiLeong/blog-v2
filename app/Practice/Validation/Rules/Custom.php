<?php

namespace App\Practice\Validation\Rules;


class Custom extends Rule
{
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function check(): bool
    {
        return $this->value === $this->string;
    }

    public function message(): string
    {
        return "The value is not $this->string";
    }
}
