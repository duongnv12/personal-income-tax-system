<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base text-gray-800 leading-tight mb-2 ">
            {{ __('Hồ sơ của bạn') }}
        </h2>
    </x-slot>

    <div class="py-8 md:py-12 bg-gray-50"> {{-- Thêm bg-gray-50 cho nền nhẹ nhàng --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Card: Cập nhật thông tin hồ sơ --}}
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-xl border border-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Card: Cập nhật mật khẩu --}}
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-xl border border-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Card: Xóa tài khoản --}}
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-xl border border-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>