<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Crypto Exchange Platform')</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Fixed Viewport Container (1421x1040) -->
    <div class="flex justify-center min-h-screen bg-gray-900 p-4">
        <div class="w-[1421px] h-[1040px] bg-white shadow-2xl overflow-hidden flex flex-col">
            
            <!-- Top Navigation Bar -->
            @include('components.navbar')

            <!-- Main Content Area with Sidebar -->
            <div class="flex flex-1 overflow-hidden">
                
                <!-- Sidebar -->
                @include('components.sidebar')

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Scripts -->
    <script>
        // CSRF Token for AJAX requests
        window.axios = require('axios');
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

        // Toast notification function
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                type === 'warning' ? 'bg-yellow-500' : 
                'bg-blue-500'
            }`;
            toast.textContent = message;
            
            const container = document.getElementById('toast-container');
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        };

        // Display Laravel flash messages
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        
        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif
    </script>

    @stack('scripts')
</body>
</html>
