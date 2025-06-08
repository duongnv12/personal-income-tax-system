<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Người dùng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6"> {{-- Dùng space-y để tạo khoảng cách giữa các trường --}}
                            <div>
                                <x-input-label for="name" :value="__('Tên')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('name', $user->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('email', $user->email)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="flex items-center mt-4">
                                <input type="checkbox" id="is_admin" name="is_admin" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $user->is_admin ? 'checked' : '' }}>
                                <x-input-label for="is_admin" class="ml-2 text-base" :value="__('Là Admin')" /> {{-- Tăng kích thước font cho label checkbox --}}
                                <x-input-error class="mt-2" :messages="$errors->get('is_admin')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="password" :value="__('Mật khẩu mới (để trống nếu không muốn đổi)')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" autocomplete="new-password" />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu mới')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" autocomplete="new-password" />
                                <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                            </div>
                        </div> {{-- Kết thúc space-y-6 --}}

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button class="ml-4 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                {{ __('Cập nhật') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>