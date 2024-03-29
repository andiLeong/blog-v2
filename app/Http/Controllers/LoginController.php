<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        abort_if(!Auth::attempt($credentials), 403,'Cant Sign In , please check your credentials');
        $request->session()->regenerate();
        $user = auth()->user();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'is_admin' => $user->isAdmin(),
        ];
    }
}
