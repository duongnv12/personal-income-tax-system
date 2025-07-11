<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-sack-dollar mr-2 text-green-600"></i> {{ __('Quản lý Khoản Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 flex items-center">
                        <i class="fa-solid fa-edit mr-2 text-purple-600"></i> {{ __('Chỉnh sửa Khoản Thu Nhập') }}: <span class="ml-2 text-blue-700">{{ $incomeEntry->incomeSource->name }} ({{ $incomeEntry->year }}@if($incomeEntry->entry_type === 'monthly') tháng {{ $incomeEntry->month }}@endif)</span>
                    </h3>

                    @if ($errors->has('period'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Lỗi nhập liệu!</p>
                            <p>{{ $errors->first('period') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('income-entries.update', $incomeEntry) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="income_source_id" :value="__('Nguồn thu nhập')" />
                            <select id="income_source_id" name="income_source_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required>
                                <option value="">Chọn nguồn thu nhập</option>
                                @foreach ($incomeSources as $source)
                                    <option value="{{ $source->id }}" {{ old('income_source_id', $incomeEntry->income_source_id) == $source->id ? 'selected' : '' }}>
                                        {{ $source->name }} (Loại:
                                        @switch($source->income_type)
                                            @case('salary') Tiền lương @break
                                            @case('business') Kinh doanh @break
                                            @case('investment') Đầu tư @break
                                            @case('other') Khác @break
                                            @default N/A
                                        @endswitch)
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('income_source_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="year" :value="__('Năm')" />
                            <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year', $incomeEntry->year)" required min="1900" max="{{ date('Y') + 1 }}" placeholder="Năm của khoản thu nhập" />
                            <x-input-error :messages="$errors->get('year')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="entry_type" :value="__('Loại nhập liệu')" />
                            <select id="entry_type" name="entry_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required onchange="toggleMonthField()">
                                <option value="monthly" {{ old('entry_type', $incomeEntry->entry_type) == 'monthly' ? 'selected' : '' }}>Hàng tháng (nhập từng tháng cụ thể)</option>
                                <option value="yearly" {{ old('entry_type', $incomeEntry->entry_type) == 'yearly' ? 'selected' : '' }}>Hàng năm (nhập cho cả năm, áp dụng cho lương ổn định)</option>
                            </select>
                            <x-input-error :messages="$errors->get('entry_type')" class="mt-2" />
                        </div>

                        <div id="month_field" class="mb-4" style="display: {{ old('entry_type', $incomeEntry->entry_type) == 'monthly' ? 'block' : 'none' }};">
                            <x-input-label for="month" :value="__('Tháng')" />
                            <x-text-input id="month" class="block mt-1 w-full" type="number" name="month" :value="old('month', $incomeEntry->month)" min="1" max="12" placeholder="Tháng của khoản thu nhập" />
                            <x-input-error :messages="$errors->get('month')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="gross_income" :value="__('Tổng thu nhập Gross (VNĐ)')" />
                            <x-text-input id="gross_income" class="block mt-1 w-full" type="text" inputmode="numeric" name="gross_income" :value="old('gross_income') ?? (isset($incomeEntry->gross_income) ? number_format($incomeEntry->gross_income) : '')" required min="0" placeholder="Tổng tiền trước thuế và các khoản khấu trừ" />
                            <x-input-error :messages="$errors->get('gross_income')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="net_income" :value="__('Thu nhập Net (thực nhận, tùy chọn)')" />
                            <x-text-input id="net_income" class="block mt-1 w-full" type="text" inputmode="numeric" name="net_income" :value="old('net_income') ?? (isset($incomeEntry->net_income) ? number_format($incomeEntry->net_income) : '')" min="0" placeholder="Số tiền thực nhận sau các khoản khấu trừ" />
                            <x-input-error :messages="$errors->get('net_income')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="tax_paid" :value="__('Thuế TNCN đã nộp (tạm tính, tùy chọn)')" />
                            <x-text-input id="tax_paid" class="block mt-1 w-full" type="text" inputmode="numeric" name="tax_paid" :value="old('tax_paid') ?? (isset($incomeEntry->tax_paid) ? number_format($incomeEntry->tax_paid) : '')" min="0" placeholder="Số tiền thuế TNCN đã nộp tạm tính" />
                            <x-input-error :messages="$errors->get('tax_paid')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="bhxh_deduction" :value="__('Khoản khấu trừ BHXH (tùy chọn)')" />
                            <x-text-input id="bhxh_deduction" class="block mt-1 w-full" type="text" inputmode="numeric" name="bhxh_deduction" :value="old('bhxh_deduction') ?? (isset($incomeEntry->bhxh_deduction) ? number_format($incomeEntry->bhxh_deduction) : '')" min="0" placeholder="Khoản khấu trừ đóng BHXH, BHYT, BHTN" />
                            <x-input-error :messages="$errors->get('bhxh_deduction')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="other_deductions" :value="__('Các khoản giảm trừ khác (tùy chọn)')" />
                            <x-text-input id="other_deductions" class="block mt-1 w-full" type="text" inputmode="numeric" name="other_deductions" :value="old('other_deductions') ?? (isset($incomeEntry->other_deductions) ? number_format($incomeEntry->other_deductions) : '')" min="0" placeholder="Các khoản giảm trừ khác chưa được đề cập" />
                            <x-input-error :messages="$errors->get('other_deductions')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> {{ __('Cập nhật Khoản Thu Nhập') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script>
        function toggleMonthField() {
            var entryType = document.getElementById('entry_type').value;
            var monthField = document.getElementById('month_field');
            var monthInput = document.getElementById('month');

            if (entryType === 'monthly') {
                monthField.style.display = 'block';
                monthInput.setAttribute('required', 'required');
            } else {
                monthField.style.display = 'none';
                monthInput.removeAttribute('required');
                monthInput.value = ''; // Clear month value when not applicable
            }
        }

        // Call the function on page load to handle old('entry_type')
        document.addEventListener('DOMContentLoaded', function() {
            toggleMonthField();
            
            // Initialize Cleave.js for number formatting
            new Cleave('#gross_income', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });

            new Cleave('#net_income', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
            
            new Cleave('#tax_paid', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
            
            new Cleave('#bhxh_deduction', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
            
            new Cleave('#other_deductions', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });

            // Handle form submission - unformat values
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    ['gross_income', 'net_income', 'tax_paid', 'bhxh_deduction', 'other_deductions'].forEach(function(id) {
                        const el = document.getElementById(id);
                        if (el && el.value) {
                           // Unformat the value before submitting
                           el.value = el.value.replace(/,/g, '');
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>