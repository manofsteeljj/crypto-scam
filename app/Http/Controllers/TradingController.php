<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\Wallet;
use App\Services\CryptoService;
use App\Services\TradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradingController extends Controller
{
    protected $cryptoService;
    protected $tradingService;

    public function __construct(CryptoService $cryptoService, TradingService $tradingService)
    {
        $this->cryptoService = $cryptoService;
        $this->tradingService = $tradingService;
    }

    /**
     * Show spot trading page.
     */
    public function spot(Request $request)
    {
        $pair = $request->get('pair', 'BTC/USDT');
        
        // Get market data
        $marketData = $this->cryptoService->getMarketData($pair);
        
        // Get order book
        $orderBook = $this->cryptoService->getOrderBook($pair);
        
        // Get user's recent trades for this pair
        $userTrades = Auth::user()->trades()
            ->where('pair', $pair)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        // Get user's wallets
        $wallets = Auth::user()->wallets;
        
        return view('trading.spot', compact(
            'pair',
            'marketData',
            'orderBook',
            'userTrades',
            'wallets'
        ));
    }

    /**
     * Show futures trading page.
     */
    public function futures(Request $request)
    {
        $pair = $request->get('pair', 'BTC/USDT');
        
        // Get market data
        $marketData = $this->cryptoService->getMarketData($pair);
        
        // Get user's open positions
        $openPositions = Auth::user()->trades()
            ->where('pair', $pair)
            ->where('status', 'pending')
            ->get();
        
        return view('trading.futures', compact(
            'pair',
            'marketData',
            'openPositions'
        ));
    }

    /**
     * Execute buy order.
     */
    public function buy(Request $request)
    {
        $request->validate([
            'pair' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'type' => 'required|in:market,limit',
        ]);

        try {
            DB::beginTransaction();

            $trade = $this->tradingService->executeBuy(
                Auth::user(),
                $request->pair,
                $request->amount,
                $request->price,
                $request->type
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Buy order placed successfully',
                'trade' => $trade,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Execute sell order.
     */
    public function sell(Request $request)
    {
        $request->validate([
            'pair' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'type' => 'required|in:market,limit',
        ]);

        try {
            DB::beginTransaction();

            $trade = $this->tradingService->executeSell(
                Auth::user(),
                $request->pair,
                $request->amount,
                $request->price,
                $request->type
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sell order placed successfully',
                'trade' => $trade,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
