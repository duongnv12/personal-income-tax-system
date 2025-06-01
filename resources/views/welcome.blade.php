<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hệ Thống Tính Thuế TNCN') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Tùy chỉnh CSS cho hiệu ứng parallax */
        .parallax-bg {
            /* Chọn một hình ảnh nền chất lượng cao và phù hợp với chủ đề của bạn */
            background-image: url('https://images.unsplash.com/photo-1543286300-84a8677c71d9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80'); /* Thay thế bằng ảnh của bạn */
            background-attachment: fixed; /* Tạo hiệu ứng parallax */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Thêm một lớp phủ mờ cho hình ảnh nền */
        .overlay-dark {
            background-color: rgba(0, 0, 0, 0.6); /* Lớp phủ đen 60% */
        }
        .overlay-gradient {
            background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.7) 100%);
        }

        /* Hiệu ứng mờ dần khi cuộn - có thể cần JS nếu muốn phức tạp hơn */
        /* Ở đây dùng CSS đơn giản, phần tử sẽ mờ dần khi cuộn nếu có overflow */
        .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            transition-delay: 0.2s; /* Trễ để có hiệu ứng đẹp hơn */
        }
        /* Class sẽ được thêm bằng JS khi phần tử xuất hiện trong viewport */
        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="relative min-h-screen flex flex-col parallax-bg">
        {{-- Overlay for better text readability --}}
        <div class="absolute inset-0 overlay-dark overlay-gradient"></div>

        {{-- Navbar --}}
        <div class="relative z-20 flex justify-between items-center p-6 lg:px-8 bg-black bg-opacity-30 backdrop-blur-sm shadow-lg">
            <a href="{{ url('/') }}" class="text-2xl font-extrabold text-white tracking-wider flex items-center">
                <svg class="h-8 w-8 mr-2 text-indigo-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 002-2V4a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                {{ config('app.name', 'Tax System') }}
            </a>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-white hover:text-indigo-300 font-medium transition duration-300">
                        {{ __('Bảng điều khiển') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-indigo-300 font-medium transition duration-300">
                        {{ __('Đăng nhập') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 px-5 py-2 border border-indigo-400 text-indigo-400 rounded-full hover:bg-indigo-400 hover:text-white transition duration-300">
                            {{ __('Đăng ký') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="relative z-10 flex-grow flex items-center justify-center py-16">
            <div class="text-center text-white p-8 md:p-12 rounded-lg max-w-4xl mx-auto fade-in-section">
                <h1 class="text-6xl md:text-7xl font-extrabold leading-tight mb-6 tracking-tight font-heading">
                    {{ config('app.name', 'Hệ Thống Tính Thuế TNCN') }}
                </h1>
                <p class="text-xl md:text-2xl mb-10 leading-relaxed font-light">
                    {{ __('Quản lý, tính toán thuế thu nhập cá nhân chưa bao giờ dễ dàng đến thế.') }}
                    <br class="hidden md:block"> {{ __('Tiết kiệm thời gian, đảm bảo chính xác và tuân thủ.') }}
                </p>
                <div class="space-y-4 sm:space-y-0 sm:space-x-6 flex flex-col sm:flex-row justify-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full shadow-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 transform hover:scale-105">
                            {{ __('Bảng điều khiển của tôi') }} &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full shadow-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 transform hover:scale-105">
                            {{ __('Bắt đầu ngay') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-lg font-bold rounded-full shadow-lg text-white bg-transparent hover:bg-white hover:text-indigo-800 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-white transition duration-300 transform hover:scale-105">
                                {{ __('Đăng ký') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="relative z-10 flex justify-center items-center p-4 text-gray-400 text-sm bg-black bg-opacity-30 backdrop-blur-sm">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) - &copy; 2025 {{ config('app.name', 'Tax System') }}. All rights reserved.
        </div>
    </div>

    {{-- Script for fade-in effect --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('.fade-in-section');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.1 }); // Khi 10% phần tử hiển thị trong viewport

            sections.forEach(section => {
                observer.observe(section);
            });
        });
    </script>
</body>
</html>