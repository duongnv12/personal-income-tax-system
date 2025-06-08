<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <button type="button" aria-label="Hiện/Ẩn mật khẩu" onclick="togglePassword('password', this)"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path class="eye-closed hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3l18 18M9.88 9.88A3 3 0 0012 15a3 3 0 002.12-5.12M6.1 6.1C4.06 7.94 2.46 10.36 2.46 12c1.27 4.06 5.06 7 9.54 7 1.61 0 3.13-.38 4.44-1.06M17.9 17.9C19.94 16.06 21.54 13.64 21.54 12c-1.27-4.06-5.06-7-9.54-7-1.61 0-3.13.38-4.44 1.06" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <button type="button" aria-label="Hiện/Ẩn mật khẩu" onclick="togglePassword('password_confirmation', this)"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path class="eye-closed hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3l18 18M9.88 9.88A3 3 0 0012 15a3 3 0 002.12-5.12M6.1 6.1C4.06 7.94 2.46 10.36 2.46 12c1.27 4.06 5.06 7 9.54 7 1.61 0 3.13-.38 4.44-1.06M17.9 17.9C19.94 16.06 21.54 13.64 21.54 12c-1.27-4.06-5.06-7-9.54-7-1.61 0-3.13.38-4.44 1.06" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const svg = btn.querySelector('svg');
            const open = svg.querySelectorAll('.eye-open');
            const closed = svg.querySelector('.eye-closed');
            if (input.type === "password") {
                input.type = "text";
                btn.classList.add('text-indigo-600');
                open.forEach(e => e.classList.add('hidden'));
                closed.classList.remove('hidden');
            } else {
                input.type = "password";
                btn.classList.remove('text-indigo-600');
                open.forEach(e => e.classList.remove('hidden'));
                closed.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>