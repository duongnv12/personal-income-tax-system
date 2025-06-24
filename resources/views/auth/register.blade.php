<x-guest-layout>
    <h2 class="text-2xl font-bold text-indigo-700 mb-6 text-center">Đăng ký tài khoản mới</h2>
    <form method="POST" action="{{ route('register') }}" class="animate__animated animate__fadeIn">
        @csrf

        <!-- Họ và tên -->
        <div>
            <x-input-label for="name" :value="__('Họ và tên')" class="text-indigo-700" />
            <x-text-input id="name"
                class="block mt-1 w-full transition duration-300 focus:ring-2 focus:ring-indigo-400 border-indigo-300"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Địa chỉ Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Địa chỉ Email')" class="text-indigo-700" />
            <x-text-input id="email"
                class="block mt-1 w-full transition duration-300 focus:ring-2 focus:ring-indigo-400 border-indigo-300"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mật khẩu -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mật khẩu')" class="text-indigo-700" />

            <x-text-input id="password"
                class="block mt-1 w-full transition duration-300 focus:ring-2 focus:ring-indigo-400 border-indigo-300"
                type="password" name="password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Xác nhận mật khẩu -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" class="text-indigo-700" />

            <x-text-input id="password_confirmation"
                class="block mt-1 w-full transition duration-300 focus:ring-2 focus:ring-indigo-400 border-indigo-300"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-indigo-600 hover:text-indigo-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300"
                href="{{ route('login') }}">
                {{ __('Đã có tài khoản?') }}
            </a>

            <x-primary-button
                class="ms-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-semibold transition duration-300 hover:scale-105 shadow-lg">
                {{ __('Đăng ký') }}
            </x-primary-button>
        </div>
    </form>
    <!-- Thêm link animate.css nếu chưa có -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</x-guest-layout>