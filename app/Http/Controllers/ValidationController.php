<?php

namespace App\Http\Controllers;

use App\Practice\Validation\Rules\Custom;
use App\Practice\Validation\Validator;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = new Validator($request->all());
        $data = $validator->validate([
            'name' => 'required|min:3|max:10|ends_with:z',
            'email' => ['required', 'email','starts_with:a'],
            'status' => 'required|in:0,1',
            'age' => 'required_if:name|between:18,60,99',
            'custom' => ['required', new Custom('answer')],
            'closure' => [fn($value, $key, $data) => $value === 'closure'],
        ], [
            'custom.required' => 'you must fill in custom field',
            'closure.closure' => 'a custom closure error message',
            'in' => 'The :key must in :argument, you had provided :value',
            'age.required_if' => 'age is required if name is provided'
        ]);
        return $data;

    }
}
