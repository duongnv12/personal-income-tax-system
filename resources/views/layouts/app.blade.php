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
</body>
</html>