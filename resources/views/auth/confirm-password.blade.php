<x-guest-layout>
    <div class="mb-6 text-base text-gray-700 leading-relaxed"> {{-- Tăng cỡ chữ và giãn dòng --}}
        {{ __('Đây là khu vực bảo mật của ứng dụng. Vui lòng xác nhận mật khẩu của bạn trước khi tiếp tục.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6"> {{-- Thêm space-y cho khoảng cách đều --}}
        @csrf

        <div>
            <x-input-label for="password" :value="__('Mật khẩu')" class="block text-sm font-medium text-gray-700" />
            <x-text-input id="password" class="block mt-2 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="flex justify-end mt-6"> {{-- Điều chỉnh mt-4 thành mt-6 để có khoảng cách tốt hơn --}}
            <x-primary-button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 focus:ring-indigo-500 shadow-md transform hover:scale-[1.02] transition-all duration-200">
                {{ __('Xác nhận') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>