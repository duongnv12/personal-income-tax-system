<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts: Inter for modern look, Figtree for fallback -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Vite for Tailwind CSS and your custom JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Ưu tiên Inter font */
            -webkit-font-smoothing: antialiased;
            /* Làm mịn font chữ cho trải nghiệm tốt hơn */
            -moz-osx-font-smoothing: grayscale;
            /* Tương tự cho Firefox */
            background: linear-gradient(to right bottom, #e0f2fe, #c4e4ff);
            /* Gradient nền xanh nhạt, tinh tế hơn */
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0"> {{-- Căn giữa hoàn toàn theo chiều
        dọc và ngang --}}
        <div>
            <a href="/">
                {{-- Logo lớn hơn một chút và màu sắc hiện đại --}}
                <x-application-logo
                    class="w-24 h-24 fill-current text-indigo-600 hover:text-indigo-700 transition-colors duration-200 transform hover:scale-105 drop-shadow-lg" />
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-8 px-8 py-6 bg-white shadow-2xl overflow-hidden rounded-xl border border-gray-100 transform hover:shadow-3xl transition-all duration-300 animate-fade-in">
            {{-- Card nội dung với hiệu ứng hiện đại --}}
            {{ $slot }}
        </div>
    </div>
    <footer class="w-full text-center text-xs text-gray-400 mt-10 mb-2 animate-fade-in-up">
        © {{ date('Y') }} <span class="font-semibold text-blue-700">Phenikaa Group</span>. All rights reserved.
    </footer>
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.7s cubic-bezier(.4, 0, .2, 1) both;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s cubic-bezier(.4, 0, .2, 1) both;
        }
    </style>
</body>

</html>