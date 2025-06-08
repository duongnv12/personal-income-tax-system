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
                    <form method="POST" action="{{ route('admin.tax-brackets.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="level" :value="__('Cấp độ')" />
                            <x-text-input id="level" name="level" type="number" class="mt-1 block w-full" :value="old('level')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('level')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="income_from" :value="__('Thu nhập từ (VNĐ)')" />
                            <x-text-input id="income_from" name="income_from" type="number" step="1" class="mt-1 block w-full" :value="old('income_from')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('income_from')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="income_to" :value="__('Thu nhập đến (VNĐ) (Để trống nếu là bậc cuối)')" />
                            <x-text-input id="income_to" name="income_to" type="number" step="1" class="mt-1 block w-full" :value="old('income_to')" />
                            <x-input-error class="mt-2" :messages="$errors->get('income_to')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tax_rate" :value="__('Thuế suất (%)')" />
                            <x-text-input id="tax_rate" name="tax_rate" type="number" step="0.01" min="0" max="100" class="mt-1 block w-full" :value="old('tax_rate') ? old('tax_rate') * 100 : ''" required />
                            <x-input-error class="mt-2" :messages="$errors->get('tax_rate')" />
                            <small class="text-gray-500">Nhập dưới dạng phần trăm (ví dụ: 5 cho 5%, 10 cho 10%)</small>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const taxRateInput = document.getElementById('tax_rate');
        // Chuyển đổi giá trị hiển thị từ % sang thập phân khi gửi form
        document.querySelector('form').addEventListener('submit', function() {
            if (taxRateInput.value !== '') {
                taxRateInput.value = parseFloat(taxRateInput.value) / 100;
            }
        });
    });
</script>