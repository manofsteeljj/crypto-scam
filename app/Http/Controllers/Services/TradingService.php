<?php

namespace App\Services;

use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

/**
 * Service for handling trading operations.
 */
class TradingService
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * Execute a buy order.
     */
    public function executeBuy(User $user, string $pair, float $amount, ?float $price, string $type)
    {
        [$baseCurrency, $quoteCurrency] = explode('/', $pair);

        // Get current market price if market order
        if ($type === 'market') {
            $price = $this->cryptoService->getPrice($baseCurrency, $quoteCurrency);
        }

        $total = $amount * $price;
        $fee = $total * 0.001; // 0.1% trading fee
        $totalWithFee = $total + $fee;

        // Get user's quote currency wallet (what they're paying with)
        $quoteWallet = $user->wallets()->where('currency', $quoteCurrency)->first();

        if (!$quoteWallet || $quoteWallet->available_balance < $totalWithFee) {
            throw new \Exception("Insufficient {$quoteCurrency} balance");
        }

        // Get user's base currency wallet (what they're buying)
        $baseWallet = $user->wallets()->where('currency', $baseCurrency)->first();

        if (!$baseWallet) {
            throw new \Exception("Wallet not found for {$baseCurrency}");
        }

        // Deduct from quote wallet
        $quoteWallet->debit($totalWithFee);

        // Add to base wallet
        $baseWallet->credit($amount);

        // Create trade record
        $trade = Trade::create([
            'user_id' => $user->id,
            'pair' => $pair,
            'type' => 'buy',
            'order_type' => $type,
            'amount' => $amount,
            'price' => $price,
            'total' => $total,
            'fee' => $fee,
            'filled_amount' => $amount,
            'status' => 'filled',
            'filled_at' => now(),
        ]);

        // Create transaction records
        Transaction::create([
            'user_id' => $user->id,
            'wallet_id' => $quoteWallet->id,
            'type' => 'trade',
            'currency' => $quoteCurrency,
            'amount' => -$totalWithFee,
            'fee' => $fee,
            'status' => 'completed',
            'description' => "Buy {$amount} {$baseCurrency} @ {$price} {$quoteCurrency}",
            'metadata' => json_encode(['trade_id' => $trade->id]),
            'completed_at' => now(),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'wallet_id' => $baseWallet->id,
            'type' => 'trade',
            'currency' => $baseCurrency,
            'amount' => $amount,
            'fee' => 0,
            'status' => 'completed',
            'description' => "Buy {$amount} {$baseCurrency} @ {$price} {$quoteCurrency}",
            'metadata' => json_encode(['trade_id' => $trade->id]),
            'completed_at' => now(),
        ]);

        return $trade;
    }

    /**
     * Execute a sell order.
     */
    public function executeSell(User $user, string $pair, float $amount, ?float $price, string $type)
    {
        [$baseCurrency, $quoteCurrency] = explode('/', $pair);

        // Get current market price if market order
        if ($type === 'market') {
            $price = $this->cryptoService->getPrice($baseCurrency, $quoteCurrency);
        }

        $total = $amount * $price;
        $fee = $total * 0.001; // 0.1% trading fee
        $totalAfterFee = $total - $fee;

        // Get user's base currency wallet (what they're selling)
        $baseWallet = $user->wallets()->where('currency', $baseCurrency)->first();

        if (!$baseWallet || $baseWallet->available_balance < $amount) {
            throw new \Exception("Insufficient {$baseCurrency} balance");
        }

        // Get user's quote currency wallet (what they're receiving)
        $quoteWallet = $user->wallets()->where('currency', $quoteCurrency)->first();

        if (!$quoteWallet) {
            throw new \Exception("Wallet not found for {$quoteCurrency}");
        }

        // Deduct from base wallet
        $baseWallet->debit($amount);

        // Add to quote wallet
        $quoteWallet->credit($totalAfterFee);

        // Create trade record
        $trade = Trade::create([
            'user_id' => $user->id,
            'pair' => $pair,
            'type' => 'sell',
            'order_type' => $type,
            'amount' => $amount,
            'price' => $price,
            'total' => $total,
            'fee' => $fee,
            'filled_amount' => $amount,
            'status' => 'filled',
            'filled_at' => now(),
        ]);

        // Create transaction records
        Transaction::create([
            'user_id' => $user->id,
            'wallet_id' => $baseWallet->id,
            'type' => 'trade',
            'currency' => $baseCurrency,
            'amount' => -$amount,
            'fee' => 0,
            'status' => 'completed',
            'description' => "Sell {$amount} {$baseCurrency} @ {$price} {$quoteCurrency}",
            'metadata' => json_encode(['trade_id' => $trade->id]),
            'completed_at' => now(),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'wallet_id' => $quoteWallet->id,
            'type' => 'trade',
            'currency' => $quoteCurrency,
            'amount' => $totalAfterFee,
            'fee' => $fee,
            'status' => 'completed',
            'description' => "Sell {$amount} {$baseCurrency} @ {$price} {$quoteCurrency}",
            'metadata' => json_encode(['trade_id' => $trade->id]),
            'completed_at' => now(),
        ]);

        return $trade;
    }
}
