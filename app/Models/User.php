<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'avatar',
        'full_name',
        'date_of_birth',
        'country',
        'address',
        'city',
        'postal_code',
        'kyc_status',
        'two_factor_enabled',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'two_factor_enabled' => 'boolean',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the wallets for the user.
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the trades for the user.
     */
    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * Get the KYC verification for the user.
     */
    public function kycVerification()
    {
        return $this->hasOne(KycVerification::class);
    }

    /**
     * Get the security devices for the user.
     */
    public function devices()
    {
        return $this->hasMany(SecurityDevice::class);
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the API keys for the user.
     */
    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    /**
     * Get total portfolio value in USDT
     */
    public function getTotalPortfolioValueAttribute()
    {
        // Calculate total portfolio value
        return $this->wallets->sum(function ($wallet) {
            return $wallet->balance * $wallet->current_price_in_usdt;
        });
    }

    /**
     * Check if user has completed KYC
     */
    public function hasCompletedKyc()
    {
        return $this->kyc_status === 'verified';
    }

    /**
     * Check if user has 2FA enabled
     */
    public function hasTwoFactorEnabled()
    {
        return $this->two_factor_enabled;
    }
}
