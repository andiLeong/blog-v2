<?php

namespace App\Practice\Validation;

use App\Exceptions\CustomValidationException;
use App\Practice\Validation\Rules\Rule;
use Closure;
use Illuminate\Http\Request;

class Validator
{

    private $rules;
    private $request;
    private $messages;
    private $hasErrors = false;

    public function __construct(Request $request, $rules = [])
    {
        $this->rules = $rules;
        $this->request = $request;
    }

    public function validate(array $rules, array $message = [])
    {
        $this->messages = $message;
        $results = collect($rules)
            ->flatMap(fn($rule, $key) => $this->createRule(
                $this->parseRuleToArray($rule), $key
            ))
            ->reduce(function ($carry, $rule) {
                $result = $rule->check();
                if (!$result) {
                    $this->hasErrors = true;
                    $carry['errors'][$rule->key()][] = $this->getMessageOf($rule);
                }
                return $carry;
            }, []);

        if ($this->hasErrors) {
            throw new CustomValidationException($results);
        }

        return $this->request->only(array_keys($rules));
    }

    /**
     * create a rule collection that contains a rule instance
     * @param array $rules
     * @param $key
     * @return array
     */
    private function createRule(array $rules, $key): array
    {
        return array_map(
            fn($rule) => $this->buildRuleInstance($rule, $key),
            $rules
        );
    }

    /**
     * @param $rule
     * @param $key
     * @param $value
     * @return RuleFactory
     */
    function getRuleFactory($rule, $key, $value): RuleFactory
    {
        return new RuleFactory($rule, $key, $value);
    }

    /**
     * convert a string rule to an array
     * @param $rule
     * @return false|mixed|string[]
     */
    function parseRuleToArray($rule)
    {
        if (is_string($rule)) {
            $rule = explode('|', $rule);
        }
        return $rule;
    }

    /**
     * @param $rule
     * @param $key
     * @return Rule
     */
    function buildRuleInstance($rule, $key)
    {
        $value = $this->request->get($key);
        if ($rule instanceof Rule) {
            $rule->setValue($value)
                ->setKey($key);
            return $rule;
        }

        $method = $rule instanceof Closure ? 'makeAnonymous' : 'make';

        return $this
            ->getRuleFactory($rule, $key, $this->request->get($key))
            ->$method();
    }

    /**
     * get an error message of a rule instance
     * @param $rule Rule
     * @return mixed
     */
    function getMessageOf(Rule $rule)
    {
        $messageKey = $rule->key() . "." . $rule->getBaseName();
        if (array_key_exists($messageKey, $this->messages)) {
            return $this->messages[$messageKey];
        }

        return $rule->message();
    }
}
