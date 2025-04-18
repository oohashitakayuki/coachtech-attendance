<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/attendance');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
