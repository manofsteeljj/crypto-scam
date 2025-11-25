<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoEx - Your Trusted Cryptocurrency Exchange</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 min-h-screen">
    <!-- Fixed Viewport Container -->
    <div class="flex justify-center min-h-screen p-4">
        <div class="w-[1421px] h-[1040px] bg-white shadow-2xl overflow-auto">
            
            <!-- Navigation Bar -->
            <nav class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🔷</span>
                        <span class="text-xl">CryptoEx</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 bg-white text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            Sign Up
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 px-8 py-16">
                <div class="max-w-6xl mx-auto text-center">
                    <h1 class="text-5xl text-gray-900 mb-6">
                        Trade Cryptocurrency with Confidence
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                        Join millions of traders on the world's leading cryptocurrency exchange platform. 
                        Buy, sell, and trade Bitcoin, Ethereum, and hundreds of other cryptocurrencies.
                    </p>
                    <div class="flex items-center justify-center gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl text-lg">
                            Get Started Now
                        </a>
                        <a href="{{ route('markets') }}" class="px-8 py-4 bg-white text-blue-600 rounded-xl hover:bg-gray-50 transition-all border-2 border-blue-600 text-lg">
                            Explore Markets
                        </a>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="px-8 py-16 bg-white">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-3xl text-center text-gray-900 mb-12">Why Choose CryptoEx?</h2>
                    
                    <div class="grid grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="text-center p-6 rounded-xl bg-blue-50">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                                🔒
                            </div>
                            <h3 class="text-xl text-gray-900 mb-2">Secure Trading</h3>
                            <p class="text-gray-600">
                                Industry-leading security measures including 2FA, cold storage, and encryption
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="text-center p-6 rounded-xl bg-purple-50">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                                ⚡
                            </div>
                            <h3 class="text-xl text-gray-900 mb-2">Fast Execution</h3>
                            <p class="text-gray-600">
                                Lightning-fast order execution with real-time market data and instant trades
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="text-center p-6 rounded-xl bg-indigo-50">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                                💰
                            </div>
                            <h3 class="text-xl text-gray-900 mb-2">Low Fees</h3>
                            <p class="text-gray-600">
                                Competitive trading fees starting at 0.1% with volume discounts available
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="px-8 py-16 bg-gradient-to-r from-blue-600 to-purple-600">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-4 gap-8 text-center text-white">
                        <div>
                            <div class="text-4xl mb-2">$2.5T+</div>
                            <div class="text-blue-100">Total Market Cap</div>
                        </div>
                        <div>
                            <div class="text-4xl mb-2">350+</div>
                            <div class="text-blue-100">Cryptocurrencies</div>
                        </div>
                        <div>
                            <div class="text-4xl mb-2">10M+</div>
                            <div class="text-blue-100">Active Users</div>
                        </div>
                        <div>
                            <div class="text-4xl mb-2">24/7</div>
                            <div class="text-blue-100">Support Available</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Cryptocurrencies -->
            <div class="px-8 py-16 bg-gray-50">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-3xl text-center text-gray-900 mb-12">Popular Cryptocurrencies</h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- BTC -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xl">
                                        ₿
                                    </div>
                                    <div>
                                        <div class="text-lg text-gray-900">Bitcoin</div>
                                        <div class="text-sm text-gray-500">BTC</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl text-gray-900">$43,250.00</div>
                                    <div class="text-sm text-green-600">+2.5%</div>
                                </div>
                            </div>
                        </div>

                        <!-- ETH -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center text-white text-xl">
                                        Ξ
                                    </div>
                                    <div>
                                        <div class="text-lg text-gray-900">Ethereum</div>
                                        <div class="text-sm text-gray-500">ETH</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl text-gray-900">$2,280.50</div>
                                    <div class="text-sm text-green-600">+3.2%</div>
                                </div>
                            </div>
                        </div>

                        <!-- BNB -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white text-xl">
                                        B
                                    </div>
                                    <div>
                                        <div class="text-lg text-gray-900">Binance Coin</div>
                                        <div class="text-sm text-gray-500">BNB</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl text-gray-900">$315.80</div>
                                    <div class="text-sm text-red-600">-1.5%</div>
                                </div>
                            </div>
                        </div>

                        <!-- SOL -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-xl">
                                        S
                                    </div>
                                    <div>
                                        <div class="text-lg text-gray-900">Solana</div>
                                        <div class="text-sm text-gray-500">SOL</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl text-gray-900">$105.40</div>
                                    <div class="text-sm text-green-600">+5.8%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-8">
                        <a href="{{ route('markets') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            View All Markets →
                        </a>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="px-8 py-16 bg-white">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-3xl text-gray-900 mb-4">Ready to Start Trading?</h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Join CryptoEx today and experience the future of cryptocurrency trading
                    </p>
                    <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl text-lg">
                        Create Free Account
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-gray-900 text-white px-8 py-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-4 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg mb-4">About</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="#" class="hover:text-white">About Us</a></li>
                                <li><a href="#" class="hover:text-white">Careers</a></li>
                                <li><a href="#" class="hover:text-white">Press</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg mb-4">Products</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="{{ route('trading.spot') }}" class="hover:text-white">Spot Trading</a></li>
                                <li><a href="{{ route('trading.futures') }}" class="hover:text-white">Futures</a></li>
                                <li><a href="{{ route('wallet') }}" class="hover:text-white">Wallet</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg mb-4">Support</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="{{ route('support') }}" class="hover:text-white">Help Center</a></li>
                                <li><a href="#" class="hover:text-white">FAQ</a></li>
                                <li><a href="#" class="hover:text-white">Contact Us</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg mb-4">Legal</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                                <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                                <li><a href="#" class="hover:text-white">Cookie Policy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                        <p>&copy; 2024 CryptoEx. All rights reserved.</p>
                        <p class="mt-2 text-sm">Demo platform - Not for real cryptocurrency trading</p>
                    </div>
                </div>
            </footer>

        </div>
    </div>
</body>
</html>
