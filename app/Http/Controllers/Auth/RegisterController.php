<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request (NO VALIDATION as per requirements).
     */
    public function register(Request $request)
    {
        // NO VALIDATION - Accept any values as per requirements
        // Data goes straight to database
        
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'kyc_status' => 'none',
            'two_factor_enabled' => false,
        ]);

        // Create default wallets for new user
        $defaultCurrencies = ['BTC', 'ETH', 'USDT', 'BNB', 'SOL', 'ADA', 'XRP', 'DOT'];
        
        foreach ($defaultCurrencies as $currency) {
            Wallet::create([
                'user_id' => $user->id,
                'currency' => $currency,
                'balance' => 0,
                'locked_balance' => 0,
                'address' => $this->generateWalletAddress($currency),
                'is_active' => true,
            ]);
        }

        // Log registration activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
            'status' => 'success',
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Account created successfully!');
    }

    /**
     * Generate a random wallet address (mock - not real blockchain address).
     */
    private function generateWalletAddress($currency)
    {
        $prefix = match($currency) {
            'BTC' => '1',
            'ETH' => '0x',
            'BNB' => 'bnb',
            'SOL' => Str::random(44),
            default => '0x',
        };

        if ($currency === 'SOL') {
            return $prefix;
        }

        return $prefix . Str::random($currency === 'BTC' ? 33 : 40);
    }
}
