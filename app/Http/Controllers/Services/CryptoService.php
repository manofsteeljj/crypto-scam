<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Service for fetching cryptocurrency data.
 * Integrates with CoinGecko API (free tier).
 */
class CryptoService
{
    protected $baseUrl = 'https://api.coingecko.com/api/v3';
    
    /**
     * Get current price for a currency pair.
     */
    public function getPrice($baseCurrency, $quoteCurrency = 'USDT')
    {
        $cacheKey = "price_{$baseCurrency}_{$quoteCurrency}";
        
        return Cache::remember($cacheKey, 60, function () use ($baseCurrency, $quoteCurrency) {
            try {
                $coinId = $this->getCoinId($baseCurrency);
                $vs_currency = strtolower($quoteCurrency);
                
                $response = Http::get("{$this->baseUrl}/simple/price", [
                    'ids' => $coinId,
                    'vs_currencies' => $vs_currency,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data[$coinId][$vs_currency] ?? 0;
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching price for {$baseCurrency}: " . $e->getMessage());
            }

            // Return mock data if API fails
            return $this->getMockPrice($baseCurrency);
        });
    }

    /**
     * Get 24h price change percentage.
     */
    public function get24hChange($currency)
    {
        $cacheKey = "change_24h_{$currency}";
        
        return Cache::remember($cacheKey, 300, function () use ($currency) {
            try {
                $coinId = $this->getCoinId($currency);
                
                $response = Http::get("{$this->baseUrl}/coins/{$coinId}", [
                    'localization' => false,
                    'tickers' => false,
                    'community_data' => false,
                    'developer_data' => false,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['market_data']['price_change_percentage_24h'] ?? 0;
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching 24h change for {$currency}: " . $e->getMessage());
            }

            return rand(-10, 10); // Mock data
        });
    }

    /**
     * Get market data for a trading pair.
     */
    public function getMarketData($pair)
    {
        [$base, $quote] = explode('/', $pair);
        
        return [
            'pair' => $pair,
            'price' => $this->getPrice($base, $quote),
            'change_24h' => $this->get24hChange($base),
            'high_24h' => $this->getPrice($base, $quote) * 1.05,
            'low_24h' => $this->getPrice($base, $quote) * 0.95,
            'volume_24h' => rand(1000000, 10000000),
        ];
    }

    /**
     * Get order book (mock data).
     */
    public function getOrderBook($pair)
    {
        [$base, $quote] = explode('/', $pair);
        $currentPrice = $this->getPrice($base, $quote);
        
        $bids = [];
        $asks = [];
        
        // Generate mock order book
        for ($i = 0; $i < 15; $i++) {
            $bids[] = [
                'price' => $currentPrice * (1 - ($i * 0.001)),
                'amount' => rand(100, 10000) / 1000,
                'total' => 0,
            ];
            
            $asks[] = [
                'price' => $currentPrice * (1 + ($i * 0.001)),
                'amount' => rand(100, 10000) / 1000,
                'total' => 0,
            ];
        }
        
        return [
            'bids' => $bids,
            'asks' => $asks,
        ];
    }

    /**
     * Get trending cryptocurrencies.
     */
    public function getTrendingCryptos($limit = 10)
    {
        return Cache::remember('trending_cryptos', 600, function () use ($limit) {
            try {
                $response = Http::get("{$this->baseUrl}/coins/markets", [
                    'vs_currency' => 'usd',
                    'order' => 'market_cap_desc',
                    'per_page' => $limit,
                    'page' => 1,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching trending cryptos: " . $e->getMessage());
            }

            return $this->getMockTrendingCryptos($limit);
        });
    }

    /**
     * Get total market cap.
     */
    public function getTotalMarketCap()
    {
        return Cache::remember('total_market_cap', 600, function () {
            try {
                $response = Http::get("{$this->baseUrl}/global");

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data']['total_market_cap']['usd'] ?? 0;
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching total market cap: " . $e->getMessage());
            }

            return 2500000000000; // Mock data: 2.5 trillion
        });
    }

    /**
     * Get Bitcoin dominance.
     */
    public function getBtcDominance()
    {
        return Cache::remember('btc_dominance', 600, function () {
            try {
                $response = Http::get("{$this->baseUrl}/global");

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data']['market_cap_percentage']['btc'] ?? 0;
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching BTC dominance: " . $e->getMessage());
            }

            return 42.5; // Mock data
        });
    }

    /**
     * Get 24h total volume.
     */
    public function getTotalVolume24h()
    {
        return Cache::remember('total_volume_24h', 600, function () {
            try {
                $response = Http::get("{$this->baseUrl}/global");

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data']['total_volume']['usd'] ?? 0;
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching 24h volume: " . $e->getMessage());
            }

            return 85000000000; // Mock data: 85 billion
        });
    }

    /**
     * Map currency symbols to CoinGecko IDs.
     */
    private function getCoinId($currency)
    {
        $map = [
            'BTC' => 'bitcoin',
            'ETH' => 'ethereum',
            'USDT' => 'tether',
            'BNB' => 'binancecoin',
            'SOL' => 'solana',
            'ADA' => 'cardano',
            'XRP' => 'ripple',
            'DOT' => 'polkadot',
            'DOGE' => 'dogecoin',
            'MATIC' => 'matic-network',
            'LTC' => 'litecoin',
            'AVAX' => 'avalanche-2',
        ];

        return $map[strtoupper($currency)] ?? strtolower($currency);
    }

    /**
     * Get mock price data (fallback).
     */
    private function getMockPrice($currency)
    {
        $mockPrices = [
            'BTC' => 43250.00,
            'ETH' => 2280.50,
            'USDT' => 1.00,
            'BNB' => 315.80,
            'SOL' => 105.40,
            'ADA' => 0.52,
            'XRP' => 0.58,
            'DOT' => 7.25,
        ];

        return $mockPrices[strtoupper($currency)] ?? 1.00;
    }

    /**
     * Get mock trending cryptos (fallback).
     */
    private function getMockTrendingCryptos($limit)
    {
        $mockData = [
            ['symbol' => 'BTC', 'name' => 'Bitcoin', 'current_price' => 43250, 'price_change_percentage_24h' => 2.5],
            ['symbol' => 'ETH', 'name' => 'Ethereum', 'current_price' => 2280, 'price_change_percentage_24h' => 3.2],
            ['symbol' => 'BNB', 'name' => 'Binance Coin', 'current_price' => 315, 'price_change_percentage_24h' => -1.5],
            ['symbol' => 'SOL', 'name' => 'Solana', 'current_price' => 105, 'price_change_percentage_24h' => 5.8],
            ['symbol' => 'ADA', 'name' => 'Cardano', 'current_price' => 0.52, 'price_change_percentage_24h' => 1.2],
            ['symbol' => 'XRP', 'name' => 'Ripple', 'current_price' => 0.58, 'price_change_percentage_24h' => -0.8],
        ];

        return array_slice($mockData, 0, $limit);
    }
}
