<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Thêm Người dùng mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.store') }}"> {{-- Đảm bảo route này tồn tại --}}
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="name" :value="__('Tên người dùng')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('name')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Địa chỉ Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('email')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="is_admin" name="is_admin" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_admin') ? 'checked' : '' }}>
                                <x-input-label for="is_admin" class="ml-2 text-base" :value="__('Là Admin')" />
                                <x-input-error class="mt-2" :messages="$errors->get('is_admin')" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Mật khẩu')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" required autocomplete="new-password" />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" required autocomplete="new-password" />
                                <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button class="ml-4 px-6 py-2.5 bg-green-600 hover:bg-green-700 focus:ring-green-500 shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                {{ __('Thêm Người dùng') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>