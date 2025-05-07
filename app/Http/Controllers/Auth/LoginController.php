<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt([
            'email' => $request->email,  
            'password' => $request->password], $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('home'))->with('success', '¡Bienvenido de nuevo, ' . Auth::user()->name . '!');
            }
        
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}
