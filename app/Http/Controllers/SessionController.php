<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create(){
        
        return view('auth.login');
    }

    public function store(){
        
        // validate
        $attributes = request()->validate([
            'email'         => ['required', 'email'],
            'password'      => ['required', Password::min(6)]
        ]);

        
        // Login attempt
        if(! Auth::attempt($attributes)){

            throw ValidationException::withMessages([
                'email' => 'Sorry, those credentials do not match..!'
            ]);

            return back()->onlyInput('email');
        }

        // Session regenerate
        request()->session()->regenerate();

        // redirect
        return redirect()->intended('/');

    }

    public function destroy(){

        Auth::logout();

        return redirect('/');
    }
}
