<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomValidationException;
use App\Practice\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = new Validator($request);
        $data = $validator->validate([
            'foo' => 'required',
            'bar' => 'required|min:3',
//            'name' => 'required|min:3|max:10',
            'email' => ['required', 'email'],
//            'status' => 'required|in:0,1',
        ]);
        return $data;

    }
}
