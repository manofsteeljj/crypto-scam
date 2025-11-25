@extends('layouts.app')

@section('title', 'Dashboard - Crypto Exchange')

@section('content')
<div class="p-6 space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
        <h1 class="text-2xl mb-2">Welcome back, {{ $user->username }}! 👋</h1>
        <p class="text-blue-100">Here's your portfolio overview and market insights.</p>
    </div>

    <!-- Portfolio Overview -->
    <div class="grid grid-cols-4 gap-4">
        <!-- Total Balance -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm mb-1">Total Balance</div>
            <div class="text-2xl text-gray-900 mb-2">
                ${{ number_format($totalPortfolioValue, 2) }}
            </div>
            <div class="flex items-center gap-1 text-sm {{ $portfolioChange24h >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    @if($portfolioChange24h >= 0)
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    @else
                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    @endif
                </svg>
                <span>{{ number_format(abs($portfolioChange24h), 2) }}%</span>
            </div>
        </div>

        <!-- Market Stats -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm mb-1">Market Cap</div>
            <div class="text-2xl text-gray-900 mb-2">
                ${{ number_format($marketStats['total_market_cap'] / 1000000000000, 2) }}T
            </div>
            <div class="text-sm text-gray-500">Total Market</div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm mb-1">BTC Dominance</div>
            <div class="text-2xl text-gray-900 mb-2">
                {{ number_format($marketStats['btc_dominance'], 1) }}%
            </div>
            <div class="text-sm text-gray-500">Bitcoin Share</div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm mb-1">24h Volume</div>
            <div class="text-2xl text-gray-900 mb-2">
                ${{ number_format($marketStats['total_volume_24h'] / 1000000000, 1) }}B
            </div>
            <div class="text-sm text-gray-500">Trading Volume</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-3 gap-6">
        <!-- Left Column: Portfolio & Trending -->
        <div class="col-span-2 space-y-6">
            <!-- My Portfolio -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg text-gray-900">My Portfolio</h2>
                </div>
                <div class="p-6">
                    @if($wallets->count() > 0)
                        <div class="space-y-3">
                            @foreach($wallets as $wallet)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white">
                                            {{ substr($wallet->currency, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-gray-900">{{ $wallet->currency }}</div>
                                            <div class="text-sm text-gray-500">{{ number_format($wallet->balance, 8) }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-gray-900">${{ number_format($wallet->value_in_usdt, 2) }}</div>
                                        <div class="text-sm {{ $wallet->change_24h >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $wallet->change_24h >= 0 ? '+' : '' }}{{ number_format($wallet->change_24h, 2) }}%
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>No assets yet. Start trading to build your portfolio!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Trending Cryptos -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg text-gray-900">Trending Cryptocurrencies</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($trendingCryptos as $crypto)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white">
                                        {{ substr($crypto['symbol'] ?? 'C', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-gray-900">{{ strtoupper($crypto['symbol'] ?? 'N/A') }}</div>
                                        <div class="text-sm text-gray-500">{{ $crypto['name'] ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-gray-900">${{ number_format($crypto['current_price'] ?? 0, 2) }}</div>
                                    <div class="text-sm {{ ($crypto['price_change_percentage_24h'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ($crypto['price_change_percentage_24h'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($crypto['price_change_percentage_24h'] ?? 0, 2) }}%
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Activity -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('wallet.deposit') }}" class="block w-full bg-green-500 text-white py-3 rounded-lg text-center hover:bg-green-600 transition-colors">
                        💰 Deposit
                    </a>
                    <a href="{{ route('trading.spot') }}" class="block w-full bg-blue-500 text-white py-3 rounded-lg text-center hover:bg-blue-600 transition-colors">
                        📈 Trade
                    </a>
                    <a href="{{ route('wallet.withdraw') }}" class="block w-full bg-purple-500 text-white py-3 rounded-lg text-center hover:bg-purple-600 transition-colors">
                        💸 Withdraw
                    </a>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg text-gray-900">Recent Activity</h2>
                </div>
                <div class="p-6">
                    @if($recentTransactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentTransactions->take(5) as $transaction)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                    <div>
                                        <div class="text-sm text-gray-900">{{ ucfirst($transaction->type) }}</div>
                                        <div class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, H:i') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->amount >= 0 ? '+' : '' }}{{ number_format($transaction->amount, 4) }} {{ $transaction->currency }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500 text-sm">
                            No recent activity
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
