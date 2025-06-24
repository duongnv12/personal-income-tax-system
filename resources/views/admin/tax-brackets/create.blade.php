<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{-- text-xl --}}
            <i class="fa-solid fa-sitemap mr-2 text-blue-600"></i> {{ __('Thêm Bậc Thuế Mới') }} {{-- Thêm icon --}}
        </h2>
    </x-slot>

    <div class="py-12"> {{-- Bỏ bg-gray-100 --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300">
                {{-- shadow-xl, border-gray-100, hiệu ứng hover --}}
                <div class="p-6 text-gray-900"> {{-- Bỏ sm:p-8 --}}
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center border-b pb-4"> {{-- font-bold,
                        border-b pb-4 --}}
                        <i class="fa-solid fa-plus-circle mr-2 text-green-600"></i> Thêm Bậc Thuế Lũy tiến Mới {{-- Thêm
                        icon --}}
                    </h3>
                    <form method="POST" action="{{ route('admin.tax-brackets.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="level" :value="__('Cấp độ')"
                                    class="text-sm font-medium text-gray-700" /> {{-- text-sm font-medium --}}
                                <x-text-input id="level" name="level" type="number"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm"
                                    :value="old('level')" required autofocus /> {{-- mt-1, text-sm --}}
                                <x-input-error class="mt-2 text-sm text-red-600" :messages="$errors->get('level')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="income_from" :value="__('Thu nhập từ (VNĐ)')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="income_from" name="income_from" type="text"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm"
                                    :value="old('income_from') ? number_format(old('income_from')) : ''" required />
                                <x-input-error class="mt-2 text-sm text-red-600"
                                    :messages="$errors->get('income_from')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="income_to" :value="__('Thu nhập đến (VNĐ)')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="income_to" name="income_to" type="text"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm"
                                    :value="old('income_to') ? number_format(old('income_to')) : ''" />
                                <x-input-error class="mt-2 text-sm text-red-600"
                                    :messages="$errors->get('income_to')" />
                                <small class="text-gray-500 mt-1 block">Để trống nếu đây là bậc thuế cuối cùng không có
                                    giới hạn trên.</small>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="tax_rate" :value="__('Thuế suất (%)')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="tax_rate" name="tax_rate" type="text"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm"
                                    :value="old('tax_rate') ? old('tax_rate') * 100 : ''" required />
                                <x-input-error class="mt-2 text-sm text-red-600" :messages="$errors->get('tax_rate')" />
                                <small class="text-gray-500 mt-1 block">Nhập giá trị phần trăm (ví dụ: <span
                                        class="font-semibold">5</span> cho 5%, <span class="font-semibold">10.5</span>
                                    cho 10.5%).</small>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('admin.tax-brackets.index') }}"
                                class="inline-flex items-center px-5 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md mr-4">
                                {{-- px-5 py-2, text-xs, shadow-md --}}
                                <i class="fa-solid fa-ban mr-2"></i> {{ __('Hủy') }}
                            </a>
                            <x-primary-button
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 shadow-md">
                                {{-- px-5 py-2, shadow-md --}}
                                <i class="fa-solid fa-plus-circle mr-2"></i> {{ __('Thêm') }} {{-- Thay icon SVG bằng
                                Font Awesome --}}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script for tax_rate conversion & format number for income fields --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action*="tax-brackets"]');
            if (form) {
                const incomeFrom = form.querySelector('#income_from');
                const incomeTo = form.querySelector('#income_to');

                if (incomeFrom) incomeFrom.value = formatNumber(incomeFrom.value.replace(/[^\d]/g, ''));
                if (incomeTo) incomeTo.value = formatNumber(incomeTo.value.replace(/[^\d]/g, ''));

                [incomeFrom, incomeTo].forEach(function(input) {
                    if (input) {
                        input.addEventListener('input', function (e) {
                            let raw = input.value.replace(/[^\d]/g, '');
                            if (raw) {
                                input.value = formatNumber(raw);
                            } else {
                                input.value = '';
                            }
                        });
                        input.addEventListener('paste', function (e) {
                            e.preventDefault();
                            let paste = (e.clipboardData || window.clipboardData).getData('text');
                            paste = paste.replace(/[^\d]/g, '');
                            input.value = formatNumber(paste);
                        });
                    }
                });

                form.addEventListener('submit', function () {
                    const taxRateInput = form.querySelector('#tax_rate');
                    if (taxRateInput && taxRateInput.value !== '') {
                        taxRateInput.value = parseFloat(taxRateInput.value.replace(/,/g, '').replace(/%/g, '')) / 100;
                    }
                    if (incomeFrom) incomeFrom.value = incomeFrom.value.replace(/[^\d]/g, '');
                    if (incomeTo) incomeTo.value = incomeTo.value.replace(/[^\d]/g, '');
                });
            }
            function formatNumber(value) {
                if (!value) return '';
                return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });
    </script>
</x-app-layout>