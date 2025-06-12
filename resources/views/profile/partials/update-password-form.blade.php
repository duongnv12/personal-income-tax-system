<section>
    <header class="text-center mb-8"> {{-- text-center mb-8 để căn giữa và tạo khoảng cách --}}
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Cập nhật mật khẩu') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Đảm bảo tài khoản của bạn sử dụng mật khẩu dài, ngẫu nhiên để giữ an toàn.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Mật khẩu hiện tại')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Mật khẩu mới')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="flex items-center gap-4 justify-end"> {{-- justify-end --}}
            <x-primary-button class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 shadow-md">
                <i class="fa-solid fa-save mr-2"></i> {{ __('Lưu') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Đã lưu.') }}</p>
            @endif
        </div>
    </form>
</section>
