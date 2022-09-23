<?php

namespace App\Http\Controllers;

use App\Practice\Validation\Rules\Custom;
use App\Practice\Validation\Validator;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = new Validator($request);
        $data = $validator->validate([
            'foo' => 'required',
            'bar' => 'required|min:3',
            'name' => 'required|min:3|max:10',
            'email' => ['required', 'email'],
            'status' => 'required|in:0,1',
            'custom' => ['required', new Custom('answer')],
        ], [
            'custom.required' => 'you must fill in custom field'
        ]);
        return $data;

    }
}
