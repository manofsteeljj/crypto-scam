<?php

namespace App\Http\Controllers;

use App\Services\CryptoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's wallets with balances
        $wallets = $user->wallets()
            ->where('balance', '>', 0)
            ->get()
            ->map(function ($wallet) {
                $price = $this->cryptoService->getPrice($wallet->currency, 'USDT');
                $wallet->current_price = $price;
                $wallet->value_in_usdt = $wallet->balance * $price;
                $wallet->change_24h = $this->cryptoService->get24hChange($wallet->currency);
                return $wallet;
            });

        // Calculate total portfolio value
        $totalPortfolioValue = $wallets->sum('value_in_usdt');

        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get recent trades
        $recentTrades = $user->trades()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Calculate 24h portfolio change
        $portfolioChange24h = $this->calculatePortfolioChange($wallets);

        // Get trending cryptos
        $trendingCryptos = $this->cryptoService->getTrendingCryptos(6);

        // Get market stats
        $marketStats = [
            'total_market_cap' => $this->cryptoService->getTotalMarketCap(),
            'btc_dominance' => $this->cryptoService->getBtcDominance(),
            'total_volume_24h' => $this->cryptoService->getTotalVolume24h(),
        ];

        return view('dashboard.index', compact(
            'user',
            'wallets',
            'totalPortfolioValue',
            'portfolioChange24h',
            'recentTransactions',
            'recentTrades',
            'trendingCryptos',
            'marketStats'
        ));
    }

    /**
     * Calculate portfolio change percentage.
     */
    private function calculatePortfolioChange($wallets)
    {
        $totalChange = 0;
        $totalValue = 0;

        foreach ($wallets as $wallet) {
            $change = $wallet->change_24h ?? 0;
            $value = $wallet->value_in_usdt;
            $totalChange += ($change / 100) * $value;
            $totalValue += $value;
        }

        return $totalValue > 0 ? ($totalChange / $totalValue) * 100 : 0;
    }
}
