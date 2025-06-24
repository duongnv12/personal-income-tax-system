<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background: linear-gradient(135deg, #e0e7ff 0%, #f0fdfa 100%);
            /* Màu nền gradient nhẹ */
        }
        .main-bg {
            background: rgba(255,255,255,0.95);
            /* Nền trắng mờ cho nội dung chính */
            min-height: 100vh;
        }
        /* Giảm khoảng cách phía trên nếu cần */
        .custom-header {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }
        /* Tùy chỉnh shadow cho header */
        header.bg-white {
            box-shadow: 0 2px 8px 0 rgba(99,102,241,0.08);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="main-bg">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow-md custom-header">
                <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8"> {{-- Giảm py-6 thành py-3 --}}
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>
    {{-- Footer chuyên nghiệp --}}
    <footer class="bg-gradient-to-r from-blue-100 to-blue-50 border-t mt-10 py-10 w-full text-gray-700 text-sm shadow-inner animate-fade-in-up">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-20 items-start">
            <!-- Cột trái: Logo, tên dự án, slogan, map -->
            <div class="flex flex-col items-center md:items-start gap-3 h-full">
                <div class="flex items-center gap-3">
                    <img src="/build/assets/logo_PKA.jpg" alt="Phenikaa Logo" class="w-12 h-12 rounded shadow bg-white object-contain" />
                    <span class="font-extrabold text-lg text-blue-800 tracking-wide">Personal Income Tax System</span>
                </div>
                <span class="text-xs text-blue-500 italic">Giải pháp quản lý thuế cá nhân hiện đại</span>
                <div class="w-full flex justify-center md:justify-start mt-2">
                    <iframe
                        src="https://www.google.com/maps?q=XP7X%2B2F+H%C3%A0+%C4%90%C3%B4ng,+H%C3%A0+N%E1%BB%99i,+Vi%E1%BB%87t+Nam&output=embed"
                        width="100%" height="110" style="min-width:180px; max-width:320px; border:0; border-radius:14px; box-shadow:0 4px 18px #0002;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <!-- Cột giữa: Kết nối với chúng tôi -->
            <div class="flex flex-col items-center md:items-start gap-3 h-full">
                <span class="font-semibold text-gray-800 text-base mb-1">Kết nối với chúng tôi</span>
                <div class="flex gap-5 mb-2">
                    <a href="https://facebook.com/phenikaagroup" target="_blank" class="hover:text-blue-700 transition"><i class="fa-brands fa-facebook fa-xl"></i></a>
                    <a href="https://github.com/phenikaa-group" target="_blank" class="hover:text-gray-800 transition"><i class="fa-brands fa-github fa-xl"></i></a>
                </div>
            </div>
            <!-- Cột phải: Liên hệ -->
            <div class="flex flex-col items-center md:items-end gap-3 h-full">
                <span class="font-semibold text-gray-800 text-base mb-1">Liên hệ</span>
                <a href="mailto:support@phenikaa.com" class="flex items-center gap-2 hover:text-blue-600 transition"><i class="fa-solid fa-envelope fa-lg"></i> support@phenikaa.com</a>
                <a href="tel:02433685253" class="flex items-center gap-2 hover:text-blue-600 transition"><i class="fa-solid fa-phone fa-lg"></i> 024 3368 5253</a>
                <span class="flex items-center gap-2 text-gray-500"><i class="fa-solid fa-location-dot fa-lg"></i> Phenikaa Group, Hà Nội</span>
            </div>
        </div>
        <div class="mt-6 text-xs text-gray-500 text-center w-full">© {{ date('Y') }} <span class="font-semibold text-blue-700">Phenikaa Group</span>. All rights reserved.</div>
    </footer>
</body>
</html>