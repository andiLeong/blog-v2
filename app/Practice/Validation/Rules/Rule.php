<?php

namespace App\Practice\Validation\Rules;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Rule
{
    protected mixed $value;

    public function __construct(
        protected $key,
        protected $data,
        protected $arguments = []
    )
    {
        $this->value = $this->getValue();
    }

    public function setProperty($data, $key)
    {
        $this->data = $data;
        $this->key = $key;
        $this->value = $this->getValue();

        return $this;
    }

    public function key()
    {
        return $this->key;
    }

    public function getValue($key = null)
    {
        return Arr::get($this->data, $key ?? $this->key);
    }

    public function getBaseName()
    {
        if ((new ReflectionClass($this))->isAnonymous()) {
            return 'closure';
        }

        return strtolower(Str::snake(class_basename($this)));
    }

    /**
     * get error message of a rule
     * @param array $customMessage
     * @return mixed|string
     */
    public function getErrorMessages(array $customMessage): mixed
    {
        $rule = $this->getBaseName();
        $messageKey = $this->key() . "." . $rule;
        if (array_key_exists($messageKey, $customMessage)) {
            return $this->parseErrorMessage($customMessage[$messageKey]);
        }

        if (array_key_exists($rule, $customMessage)) {
            return $this->parseErrorMessage($customMessage[$rule]);
        }

        return $this->message();
    }


    private function parseErrorMessage(string $rule)
    {
        $argument = implode(',', $this->arguments);
        return str_replace(
            [':key', ':value', ':argument'],
            [$this->key, $this->value, $argument],
            $rule
        );
    }

    abstract public function check(): bool;

    abstract public function message(): string;


}
