<x-guest-layout>
    <div class="mb-6 text-base text-gray-700 leading-relaxed"> {{-- Tăng cỡ chữ và giãn dòng --}}
        {{ __('Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, bạn có thể xác minh địa chỉ email của mình bằng cách nhấp vào liên kết chúng tôi vừa gửi qua email cho bạn không? Nếu bạn không nhận được email, chúng tôi rất vui lòng gửi lại cho bạn.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-medium text-base text-green-600 text-center"> {{-- Tăng cỡ chữ và căn giữa --}}
            {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp trong quá trình đăng ký.') }}
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between"> {{-- Điều chỉnh mt-4 thành mt-6 --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 focus:ring-indigo-500 shadow-md transform hover:scale-[1.02] transition-all duration-200">
                    {{ __('Gửi lại Email xác minh') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                {{ __('Đăng xuất') }}
            </button>
        </form>
    </div>
</x-guest-layout>