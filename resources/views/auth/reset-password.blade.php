<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-6"> {{-- Thêm space-y cho khoảng cách đều --}}
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Địa chỉ Email')" class="block text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-2 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Mật khẩu mới')" class="block text-sm font-medium text-gray-700" />
            <x-text-input id="password" class="block mt-2 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu mới')" class="block text-sm font-medium text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-2 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="flex items-center justify-end mt-6"> {{-- Điều chỉnh mt-4 thành mt-6 --}}
            <x-primary-button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 focus:ring-indigo-500 shadow-md transform hover:scale-[1.02] transition-all duration-200">
                {{ __('Đặt lại mật khẩu') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>