<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
    return view('auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // Redirect berdasarkan role user
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'penulis') {
                return redirect()->route('penulis.dashboard');
            } else {
                // Jika role tidak dikenali, logout dan redirect ke login
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Role user tidak valid.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout()
    {
    Auth::logout();
    return redirect()->route('admin.login');
    }
}
