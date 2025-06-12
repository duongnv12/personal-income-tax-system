<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-user-circle mr-2 text-blue-600"></i> {{ __('Hồ sơ cá nhân') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Thông báo thành công --}}
            @if (session('status') === 'profile-updated')
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p class="font-bold">Thành công!</p>
                    <p>Thông tin hồ sơ của bạn đã được cập nhật.</p>
                </div>
            @endif

            {{-- Thông báo lỗi (nếu có) - thường sẽ xuất hiện ở từng phần form con --}}
            {{-- Ví dụ: Nếu có lỗi validate từ bất kỳ form nào, chúng sẽ hiển thị trong component đó --}}

            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300">
                <div class="max-w-xl mx-auto">
                    {{-- Tiêu đề cho phần Cập nhật thông tin profile --}}
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4 text-center">
                        <i class="fa-solid fa-address-card mr-2 text-indigo-600"></i> {{ __('Thông tin hồ sơ') }}
                    </h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300">
                <div class="max-w-xl mx-auto">
                    {{-- Tiêu đề cho phần Cập nhật mật khẩu --}}
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4 text-center">
                        <i class="fa-solid fa-key mr-2 text-yellow-600"></i> {{ __('Cập nhật mật khẩu') }}
                    </h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300">
                <div class="max-w-xl mx-auto">
                    {{-- Tiêu đề cho phần Xóa tài khoản --}}
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4 text-center">
                        <i class="fa-solid fa-user-times mr-2 text-red-600"></i> {{ __('Xóa tài khoản') }}
                    </h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
