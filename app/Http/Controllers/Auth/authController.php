<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    //

    public function loginUi(){
        return view('auth.signin');
    }
    public function registerUi(){
        return view('auth.signup');
    }
    public function forget_passwordUi(){
        return view('auth.forgetpassword');
    }

   public function login(Request $request)
{
    // 1. Validate input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    // 2. Attempt login
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        // 3. Regenerate session (security best practice)
        $request->session()->regenerate();

        $user = Auth::user();

        // 4. Redirect based on role
        if ($user->hasAnyRole ('admin','user','moderator')) {
            return redirect()->route('dashboard.ui');
        }

        return redirect()->route('login.ui');
    }

    // 5. If login fails
    return back()->withErrors([
        'email' => 'Invalid email or password',
    ])->withInput();
}

public function logout(Request $request)
{
    Auth::logout();

    // Invalidate session
    $request->session()->invalidate();

    // Regenerate CSRF token
    $request->session()->regenerateToken();

    // Redirect to login page (or homepage)
    return redirect()->route('login.ui');
}
}
