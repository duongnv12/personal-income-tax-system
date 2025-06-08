<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm Bậc Thuế Mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tax-brackets.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="level" :value="__('Bậc')" />
                            <x-text-input id="level" name="level" type="number" min="1" class="mt-1 block w-full" :value="old('level')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('level')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="income_from" :value="__('Thu nhập từ (VNĐ)')" />
                            <x-text-input id="income_from" name="income_from" type="number" step="any" min="0" class="mt-1 block w-full" :value="old('income_from')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('income_from')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="income_to" :value="__('Thu nhập đến (VNĐ) - Để trống nếu là bậc cuối cùng')" />
                            <x-text-input id="income_to" name="income_to" type="number" step="any" min="0" class="mt-1 block w-full" :value="old('income_to')" />
                            <x-input-error class="mt-2" :messages="$errors->get('income_to')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tax_rate" :value="__('Tỷ lệ thuế (ví dụ: 0.05 cho 5%)')" />
                            <x-text-input id="tax_rate" name="tax_rate" type="number" step="0.0001" min="0" max="1" class="mt-1 block w-full" :value="old('tax_rate')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('tax_rate')" />
                            <p class="text-sm text-gray-500 mt-1">Ví dụ: 0.05 (5%), 0.1 (10%), ...</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Thêm') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>