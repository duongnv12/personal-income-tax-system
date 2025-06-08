<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Tham số Thuế') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.tax-parameters.update', $taxParameter) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <x-input-label for="param_key" :value="__('Khóa tham số')" />
                            <x-text-input id="param_key" type="text" class="mt-1 block w-full bg-gray-100" :value="$taxParameter->param_key" readonly />
                        </div>

                        <div>
                            <x-input-label for="param_value" :value="__('Giá trị tham số')" />
                            <x-text-input id="param_value" name="param_value" type="number" step="1" class="mt-1 block w-full" :value="old('param_value', $taxParameter->param_value)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('param_value')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Mô tả')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $taxParameter->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Cập nhật') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>