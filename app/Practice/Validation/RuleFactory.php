<?php

namespace App\Practice\Validation;

use App\Practice\Validation\Rules\Rule;
use Illuminate\Support\Str;

class RuleFactory
{
    private $name;
    private $key;
    private $value;

    /**
     * the name of the rule we are trying yo build instance
     * @param $name
     *
     * the key of the validation checking
     * @param $key
     *
     * the value of the key comes from request
     * @param $value
     */

    public function __construct($name, $key, $value)
    {
        $this->name = $name;
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * get a rule instance back
     * @return Rule
     */
    public function make(): Rule
    {
        [$rule, $arguments] = $this->parseClassAndArguments();

        $class = 'App\\Practice\\Validation\\Rules\\' . ucfirst($rule);
        return new $class($this->key, $this->value, $arguments);
    }

    /**
     * instantiate an anonymous rule object
     * @return Rule
     */
    public function makeAnonymous()
    {
        $class = new class($this->key, $this->value) extends Rule {
            public $closure;

            public function check(): bool
            {
                return call_user_func($this->closure, $this->value);
            }

            public function message(): string
            {
                return "The $this->key is invalid";
            }
        };

        $class->closure = $this->name;
        return $class;
    }

    /**
     * get all arguments a rule object needed after the , symbol
     * @return false|string[]
     */
    public function getArguments()
    {
        $rule = Str::after($this->name, ':');
        return explode(',', $rule);
    }

    /**
     * get a class name and its additional arguments
     * @return array
     */
    private function parseClassAndArguments(): array
    {
        $rule = $this->getRuleNameBeforeColon();
        if ($rule === false) {
            return [$this->name, []];
        }

        return [
            $rule,
            $this->getArguments()
        ];
    }


    /**
     * get a rule class name before the colon
     * @return false|string
     */
    protected function getRuleNameBeforeColon()
    {
        return strstr($this->name, ':', true);
    }
}
