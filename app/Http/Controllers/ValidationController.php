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
            'name' => 'required|min:3|max:10',
            'email' => ['required', 'email'],
            'status' => 'required|in:0,1',
            'age' => 'required_if:name',
            'custom' => ['required', new Custom('answer')],
            'closure' => [fn($value, $key, $data) => $value === 'closure'],
        ], [
            'custom.required' => 'you must fill in custom field',
            'closure.closure' => 'a custom closure error message',
            'age.required_if' => 'age is required if name is provided'
        ]);
        return $data;

    }
}
