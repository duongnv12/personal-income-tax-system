<x-app-layout>
    <x-slot name="header">
        {{-- Đồng bộ header với icon và màu sắc --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-user-edit mr-2 text-purple-600"></i> {{ __('Chỉnh sửa Người dùng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Container chính với shadow-xl, bo góc và hiệu ứng hover --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center border-b pb-4">
                        <i class="fa-solid fa-user-edit mr-2 text-purple-600"></i> Chỉnh sửa Thông tin Người dùng
                    </h3>
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="name" :value="__('Tên người dùng')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Địa chỉ Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="flex items-center pt-2">
                                {{-- Checked status dựa trên giá trị hiện tại của $user->is_admin --}}
                                <input type="checkbox" id="is_admin" name="is_admin" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                <x-input-label for="is_admin" class="ml-3 text-base font-medium text-gray-700" :value="__('Là Admin')" />
                                <x-input-error class="mt-2" :messages="$errors->get('is_admin')" />
                            </div>

                            <div class="pt-2">
                                <x-input-label for="password" :value="__('Mật khẩu mới (để trống nếu không muốn đổi)')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu mới')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-5 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm mr-4">
                                <i class="fa-solid fa-ban mr-2"></i> {{ __('Hủy') }}
                            </a>
                            <x-primary-button class="px-5 py-2 bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 shadow-md">
                                <i class="fa-solid fa-save mr-2"></i> {{ __('Cập nhật Người dùng') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
