<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-building-columns mr-2 text-blue-600"></i> {{ __('Quản lý Nguồn Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 flex items-center">
                        <i class="fa-solid fa-edit mr-2 text-purple-600"></i> {{ __('Chỉnh sửa Nguồn Thu Nhập') }}: <span class="ml-2 text-blue-700">{{ $incomeSource->name }}</span>
                    </h3>

                    <form method="POST" action="{{ route('income-sources.update', $incomeSource) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Tên nguồn thu nhập')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $incomeSource->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="income_type" :value="__('Loại thu nhập')" />
                            <select id="income_type" name="income_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required>
                                <option value="">Chọn loại thu nhập</option>
                                @foreach ($incomeTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('income_type', $incomeSource->income_type) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('income_type')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="tax_code" :value="__('Mã số thuế của tổ chức/cá nhân trả thu nhập (nếu có)')" />
                            <x-text-input id="tax_code" name="tax_code" type="text" class="mt-1 block w-full" :value="old('tax_code', $incomeSource->tax_code)" />
                            <x-input-error class="mt-2" :messages="$errors->get('tax_code')" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="address" :value="__('Địa chỉ tổ chức/cá nhân trả thu nhập (nếu có)')" />
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $incomeSource->address)" />
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> {{ __('Cập nhật') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>