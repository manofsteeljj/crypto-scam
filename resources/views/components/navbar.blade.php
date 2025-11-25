<nav class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 flex items-center justify-between shadow-lg">
    <div class="flex items-center gap-6">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">
            🔷 CryptoEx
        </a>

        <!-- Main Navigation -->
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-200 transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-200' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('markets') }}" class="hover:text-blue-200 transition-colors {{ request()->routeIs('markets*') ? 'text-blue-200' : '' }}">
                Markets
            </a>
            <a href="{{ route('trading.spot') }}" class="hover:text-blue-200 transition-colors {{ request()->routeIs('trading.*') ? 'text-blue-200' : '' }}">
                Trade
            </a>
            <a href="{{ route('wallet') }}" class="hover:text-blue-200 transition-colors {{ request()->routeIs('wallet*') ? 'text-blue-200' : '' }}">
                Wallet
            </a>
        </div>
    </div>

    <!-- Right Side -->
    <div class="flex items-center gap-4">
        <!-- User Menu -->
        <div class="relative group">
            <button class="flex items-center gap-2 hover:text-blue-200 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                </div>
                <span>{{ Auth::user()->username }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 z-50">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    👤 Profile
                </a>
                <a href="{{ route('security') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    🔒 Security
                </a>
                <a href="{{ route('kyc') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    ✅ KYC Verification
                </a>
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    ⚙️ Settings
                </a>
                <hr class="my-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
