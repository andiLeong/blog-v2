<?php

namespace App\Practice\Validation;

use App\Exceptions\CustomValidationException;
use Illuminate\Http\Request;

class Validator
{

    private $rules;
    private $request;
    private $keys;
    private $hasErrors = false;

    public function __construct(Request $request, $rules = [])
    {
        $this->rules = $rules;
        $this->request = $request;
    }

    public function validate(array $rules)
    {
        $results = collect($rules)
            ->flatMap(fn($rule, $key) => $this->createRule(
                $this->parseRuleToArray($rule), $key
            ))
            ->reduce(function ($carry, $rule) {
                $result = $rule->check();
                if (!$result) {
                    $this->hasErrors = true;
                    $carry['errors'][$rule->key()][] = $rule->message();
                }
                return $carry;
            }, []);
//        dump($results);

        if ($this->hasErrors) {
            throw new CustomValidationException($results);
        }

        return $this->request->only($this->keys);
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
     * @return Rules\Rule|mixed
     */
    function buildRuleInstance($rule, $key)
    {
        if (is_object($rule)) {
            return $rule;
        }

        return $this
            ->getRuleFactory($rule, $key, $this->request->get($key))
            ->make();
    }
}
