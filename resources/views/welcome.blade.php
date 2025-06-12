<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Vite for Tailwind CSS and your custom JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right bottom, #e0f2fe, #c4e4ff); /* Light blue gradient background, refined */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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
            background: rgba(0, 0, 0, 0.05); /* Slight overlay for readability, made lighter */
            z-index: -1;
        }
        .card-glow:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15), 0 0 0 4px rgba(99, 102, 241, 0.3); /* Indigo glow on hover */
            transform: translateY(-5px); /* Add a subtle lift on hover */
        }
    </style>
</head>
<body class="antialiased text-gray-900">
    <div class="min-h-screen flex flex-col justify-between">
        {{-- Navigation Bar (Header) --}}
        <header class="py-4 px-6 bg-gradient-to-r from-white to-gray-50 shadow-lg flex justify-between items-center z-50 sticky top-0 rounded-b-lg"> {{-- Added gradient, stronger shadow, rounded bottom, sticky --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center text-3xl font-extrabold text-indigo-700 hover:text-indigo-900 transition duration-200 transform hover:scale-[1.02]"> {{-- Larger, bolder, indigo color, subtle scale on hover --}}
                    <x-application-logo class="w-10 h-10 fill-current text-indigo-600 mr-2" /> {{-- Indigo logo --}}
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <nav class="space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 ease-in-out transform hover:scale-105">
                            <i class="fa-solid fa-gauge-high mr-2"></i> Bảng điều khiển
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 ease-in-out transform hover:scale-105">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> Đăng nhập
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-md shadow-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition duration-200 ease-in-out transform hover:scale-105 ml-4">
                                <i class="fa-solid fa-user-plus mr-2"></i> Đăng ký
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        {{-- Hero Section --}}
        <main class="flex-grow flex items-center justify-center p-6">
            <div class="max-w-4xl text-center">
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6 animate-fade-in-down">
                    Quản lý <span class="text-indigo-700">Thuế TNCN</span> dễ dàng và chính xác
                </h1>
                <p class="text-xl text-gray-700 mb-10 max-w-2xl mx-auto animate-fade-in-up">
                    Hệ thống của chúng tôi giúp bạn theo dõi, tính toán và quyết toán thuế thu nhập cá nhân một cách minh bạch, tiết kiệm thời gian và giảm thiểu sai sót.
                </p>
                <div class="space-x-4 animate-fade-in-up-delay">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-10 py-5 bg-indigo-600 border border-transparent rounded-full font-bold text-lg text-white uppercase tracking-wider hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-4 ring-indigo-300 transition ease-in-out duration-300 shadow-xl transform hover:scale-105">
                                <i class="fa-solid fa-gauge-high mr-3"></i> Tới Bảng điều khiển
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-10 py-5 bg-indigo-600 border border-transparent rounded-full font-bold text-lg text-white uppercase tracking-wider hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-4 ring-indigo-300 transition ease-in-out duration-300 shadow-xl transform hover:scale-105">
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

        {{-- Features/Call to Action Section --}}
        <section class="py-16 px-6 bg-gray-50 text-center shadow-inner"> {{-- Lighter background, inner shadow --}}
            <h2 class="text-4xl font-extrabold text-gray-800 mb-12">Tại sao chọn chúng tôi?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl mx-auto">
                <div class="p-8 rounded-xl shadow-lg bg-white transform hover:-translate-y-2 transition-transform duration-300 card-glow">
                    <div class="text-indigo-500 mb-4"> {{-- Changed to indigo --}}
                        <i class="fa-solid fa-calculator text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Tính toán chính xác</h3>
                    <p class="text-gray-600">Hệ thống tự động cập nhật biểu thuế, đảm bảo kết quả tính toán thuế luôn chính xác.</p>
                </div>
                <div class="p-8 rounded-xl shadow-lg bg-white transform hover:-translate-y-2 transition-transform duration-300 card-glow">
                    <div class="text-green-600 mb-4"> {{-- Retained green, darker shade --}}
                        <i class="fa-solid fa-clock-rotate-left text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Tiết kiệm thời gian</h3>
                    <p class="text-gray-600">Quản lý tất cả dữ liệu thu nhập, giảm trừ tại một nơi, rút ngắn quy trình quyết toán.</p>
                </div>
                <div class="p-8 rounded-xl shadow-lg bg-white transform hover:-translate-y-2 transition-transform duration-300 card-glow">
                    <div class="text-purple-600 mb-4"> {{-- Retained purple, darker shade --}}
                        <i class="fa-solid fa-lock text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Bảo mật tuyệt đối</h3>
                    <p class="text-gray-600">Dữ liệu cá nhân của bạn được bảo vệ với các tiêu chuẩn bảo mật hàng đầu.</p>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="bg-gray-900 text-white py-12 px-6"> {{-- Darker background, more padding --}}
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h4 class="text-xl font-bold mb-4 text-blue-300">Về chúng tôi</h4>
                    <p class="text-gray-400 text-sm">
                        {{ config('app.name', 'Laravel') }} là giải pháp hàng đầu giúp bạn quản lý thuế thu nhập cá nhân hiệu quả, minh bạch và tiết kiệm thời gian.
                    </p>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-4 text-blue-300">Liên kết nhanh</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">Trang chủ</a></li>
                        @auth
                            <li><a href="{{ url('/dashboard') }}" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">Bảng điều khiển</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">Đăng nhập</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">Đăng ký</a></li>
                        @endauth
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-4 text-blue-300">Theo dõi chúng tôi</h4>
                    <div class="flex justify-center md:justify-start space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200"><i class="fab fa-facebook-f text-2xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200"><i class="fab fa-twitter text-2xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200"><i class="fab fa-linkedin-in text-2xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200"><i class="fab fa-instagram text-2xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Tất cả quyền được bảo lưu.</p>
                <p class="mt-2">
                    <a href="#" class="hover:underline text-gray-400">Chính sách bảo mật</a> | <a href="#" class="hover:underline text-gray-400">Điều khoản dịch vụ</a>
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
