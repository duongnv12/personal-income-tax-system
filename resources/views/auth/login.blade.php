<x-guest-layout>
    <!-- Trạng thái phiên -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

        <h2 class="text-2xl font-bold text-indigo-700 mb-6 text-center">Đăng nhập hệ thống</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Địa chỉ Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-indigo-700" />
                <x-text-input id="email" class="block mt-1 w-full transition duration-300 focus:ring-2 focus:ring-indigo-400 border-indigo-300" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Mật khẩu -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Mật khẩu')" class="text-indigo-700" />
                <x-text-input id="password" class="block mt-1 w-full transition duration-300 focus:ring-2 focus:ring-indigo-400 border-indigo-300"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Ghi nhớ đăng nhập -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-indigo-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition duration-300" name="remember">
                    <span class="ms-2 text-sm text-gray-700">{{ __('Ghi nhớ đăng nhập') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-indigo-600 hover:text-indigo-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300" href="{{ route('password.request') }}">
                        {{ __('Quên mật khẩu?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-semibold transition duration-300 hover:scale-105 shadow-lg">
                    {{ __('Đăng nhập') }}
                </x-primary-button>
            </div>

            <div class="mt-8 text-center">
                <span class="text-gray-600 text-sm">Chưa có tài khoản?</span>
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-purple-600 font-semibold underline ml-1 transition duration-300">Đăng ký ngay</a>
            </div>
        </form>
    </div>
    <!-- Thêm thư viện animate.css để có hiệu ứng động -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</x-guest-layout>
