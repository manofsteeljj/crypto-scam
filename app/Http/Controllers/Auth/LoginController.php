<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     * Handle login request (NO VALIDATION as per requirements).
     */
    public function login(Request $request)
    {
        // NO VALIDATION - Accept any values as per requirements
        $credentials = $request->only('email', 'password');
        
        // Try to find user by email or username
        $user = User::where('email', $credentials['email'])
                    ->orWhere('username', $credentials['email'])
                    ->first();

        // If user exists, log them in regardless of password (as per no validation requirement)
        if ($user) {
            Auth::login($user, $request->filled('remember'));
            
            // Update last login info
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'ip_address' => $request->ip(),
                'device' => $request->userAgent(),
                'status' => 'success',
            ]);

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        // If user doesn't exist, return back with error
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        // Log activity before logout
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'ip_address' => $request->ip(),
                'device' => $request->userAgent(),
                'status' => 'success',
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
