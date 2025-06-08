<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right bottom, #e0f2fe, #bfdbfe); /* Light blue gradient background */
        }
        .hero-section {
            background-size: cover;
            background-position: center;
            position: relative;
            z-index: 1;
        }
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1); /* Slight overlay for readability */
            z-index: -1;
        }
        .card-glow:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15), 0 0 0 4px rgba(59, 130, 246, 0.3); /* Blue glow on hover */
        }
    </style>
</head>
<body class="antialiased text-gray-900">
    <div class="min-h-screen flex flex-col justify-between">
        {{-- Navigation Bar (Optional, can be removed if a simpler header is preferred) --}}
        <header class="py-4 px-6 bg-white shadow-sm flex justify-between items-center z-10 sticky top-0">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center text-2xl font-bold text-blue-700 hover:text-blue-900 transition duration-200">
                    <x-application-logo class="w-10 h-10 fill-current text-blue-600 mr-2" />
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <nav class="space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-200">
                            Bảng điều khiển
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-200">
                            Đăng nhập
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-200 ml-4">
                                Đăng ký
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        {{-- Hero Section --}}
        <main class="flex-grow flex items-center justify-center p-6">
            <div class="max-w-4xl text-center">
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                    Quản lý <span class="text-blue-700">Thuế TNCN</span> dễ dàng và chính xác
                </h1>
                <p class="text-xl text-gray-700 mb-10 max-w-2xl mx-auto">
                    Hệ thống của chúng tôi giúp bạn theo dõi, tính toán và quyết toán thuế thu nhập cá nhân một cách minh bạch, tiết kiệm thời gian và giảm thiểu sai sót.
                </p>
                <div class="space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-10 py-5 bg-blue-600 border border-transparent rounded-full font-bold text-lg text-white uppercase tracking-wider hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-4 ring-blue-300 transition ease-in-out duration-300 shadow-xl transform hover:scale-105">
                                <i class="fa-solid fa-gauge-high mr-3"></i> Tới Bảng điều khiển
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-10 py-5 bg-blue-600 border border-transparent rounded-full font-bold text-lg text-white uppercase tracking-wider hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-4 ring-blue-300 transition ease-in-out duration-300 shadow-xl transform hover:scale-105">
                                <i class="fa-solid fa-right-to-bracket mr-3"></i> Bắt đầu ngay
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-10 py-5 bg-gray-200 border border-gray-300 rounded-full font-bold text-lg text-gray-800 uppercase tracking-wider hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-4 ring-gray-300 transition ease-in-out duration-300 shadow-xl transform hover:scale-105 ml-4">
                                    <i class="fa-solid fa-user-plus mr-3"></i> Đăng ký tài khoản
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </main>

        {{-- Features/Call to Action Section (Optional: add more sections here) --}}
        <section class="py-16 px-6 bg-white text-center shadow-lg">
            <h2 class="text-4xl font-extrabold text-gray-800 mb-12">Tại sao chọn chúng tôi?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl mx-auto">
                <div class="p-8 rounded-xl shadow-lg bg-white transform hover:-translate-y-2 transition-transform duration-300 card-glow">
                    <div class="text-blue-500 mb-4">
                        <i class="fa-solid fa-calculator text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Tính toán chính xác</h3>
                    <p class="text-gray-600">Hệ thống tự động cập nhật biểu thuế, đảm bảo kết quả tính toán thuế luôn chính xác.</p>
                </div>
                <div class="p-8 rounded-xl shadow-lg bg-white transform hover:-translate-y-2 transition-transform duration-300 card-glow">
                    <div class="text-green-500 mb-4">
                        <i class="fa-solid fa-clock-rotate-left text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Tiết kiệm thời gian</h3>
                    <Quản lý tất cả dữ liệu thu nhập, giảm trừ tại một nơi, rút ngắn quy trình quyết toán.</p>
                </div>
                <div class="p-8 rounded-xl shadow-lg bg-white transform hover:-translate-y-2 transition-transform duration-300 card-glow">
                    <div class="text-purple-500 mb-4">
                        <i class="fa-solid fa-lock text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Bảo mật tuyệt đối</h3>
                    <p class="text-gray-600">Dữ liệu cá nhân của bạn được bảo vệ với các tiêu chuẩn bảo mật hàng đầu.</p>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-8 px-6 bg-gray-800 text-white text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            <p class="mt-2 text-sm text-gray-400">
                <a href="#" class="hover:underline">Chính sách bảo mật</a> | <a href="#" class="hover:underline">Điều khoản dịch vụ</a>
            </p>
        </footer>
    </div>
</body>
</html>