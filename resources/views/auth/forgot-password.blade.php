<x-guest-layout>
    <div class="mb-6 text-base text-gray-700 leading-relaxed"> {{-- Tăng cỡ chữ và giãn dòng --}}
        {{ __('Quên mật khẩu? Đừng lo lắng. Chỉ cần cho chúng tôi biết địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn một liên kết đặt lại mật khẩu để bạn có thể chọn mật khẩu mới.') }}
    </div>

    <x-auth-session-status class="mb-6 text-center text-lg text-green-600 font-semibold" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6"> {{-- Thêm space-y cho khoảng cách đều --}}
        @csrf

        <div>
            <x-input-label for="email" :value="__('Địa chỉ Email')" class="block text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-2 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="flex items-center justify-end mt-6"> {{-- Điều chỉnh mt-4 thành mt-6 --}}
            <x-primary-button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 focus:ring-indigo-500 shadow-md transform hover:scale-[1.02] transition-all duration-200">
                {{ __('Gửi liên kết đặt lại mật khẩu') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>