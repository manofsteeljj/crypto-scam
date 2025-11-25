<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
        'balance',
        'locked_balance',
        'address',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:8',
        'locked_balance' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for the wallet.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get available balance (balance - locked)
     */
    public function getAvailableBalanceAttribute()
    {
        return $this->balance - $this->locked_balance;
    }

    /**
     * Get total balance in USDT (requires price data)
     */
    public function getValueInUsdtAttribute()
    {
        // This would fetch current price from CryptoService
        $price = app(\App\Services\CryptoService::class)->getPrice($this->currency, 'USDT');
        return $this->balance * $price;
    }

    /**
     * Lock funds for pending orders
     */
    public function lockFunds($amount)
    {
        if ($this->available_balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $this->locked_balance += $amount;
        $this->save();
    }

    /**
     * Unlock funds when order is completed or cancelled
     */
    public function unlockFunds($amount)
    {
        $this->locked_balance -= $amount;
        $this->save();
    }

    /**
     * Add funds to wallet
     */
    public function credit($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    /**
     * Deduct funds from wallet
     */
    public function debit($amount)
    {
        if ($this->available_balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $this->balance -= $amount;
        $this->save();
    }
}
