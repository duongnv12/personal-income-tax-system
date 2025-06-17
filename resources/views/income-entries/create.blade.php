<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-sack-dollar mr-2 text-green-600"></i> {{ __('Quản lý Khoản Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 flex items-center">
                        <i class="fa-solid fa-plus-circle mr-2 text-green-600"></i> {{ __('Thêm Khoản Thu Nhập Mới') }}
                    </h3>

                    @if ($errors->has('period'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Lỗi nhập liệu!</p>
                            <p>{{ $errors->first('period') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('income-entries.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="income_source_id" :value="__('Nguồn thu nhập')" />
                            <select id="income_source_id" name="income_source_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required>
                                <option value="">Chọn nguồn thu nhập</option>
                                @foreach ($incomeSources as $source)
                                    <option value="{{ $source->id }}" {{ old('income_source_id') == $source->id ? 'selected' : '' }}>
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
                            <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year', date('Y'))" required min="1900" max="{{ date('Y') + 1 }}" placeholder="Năm của khoản thu nhập" />
                            <x-input-error :messages="$errors->get('year')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="entry_type" :value="__('Loại nhập liệu')" />
                            <select id="entry_type" name="entry_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required onchange="toggleMonthField()">
                                <option value="monthly" {{ old('entry_type') == 'monthly' ? 'selected' : '' }}>Hàng tháng (nhập từng tháng cụ thể)</option>
                                <option value="yearly" {{ old('entry_type') == 'yearly' ? 'selected' : '' }}>Hàng năm (nhập cho cả năm, áp dụng cho lương ổn định)</option>
                            </select>
                            <x-input-error :messages="$errors->get('entry_type')" class="mt-2" />
                        </div>

                        <div id="month_field" class="mb-4" style="display: {{ old('entry_type', 'monthly') == 'monthly' ? 'block' : 'none' }};">
                            <x-input-label for="month" :value="__('Tháng')" />
                            <x-text-input id="month" class="block mt-1 w-full" type="number" name="month" :value="old('month', date('m'))" min="1" max="12" placeholder="Tháng của khoản thu nhập" />
                            <x-input-error :messages="$errors->get('month')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="calculation_direction" :value="__('Chiều tính lương')" />
                            <select id="calculation_direction" name="calculation_direction" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required onchange="toggleDirectionFields()">
                                <option value="gross_to_net" {{ old('calculation_direction', 'gross_to_net') == 'gross_to_net' ? 'selected' : '' }}>Gross → Net</option>
                                <option value="net_to_gross" {{ old('calculation_direction') == 'net_to_gross' ? 'selected' : '' }}>Net → Gross</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="region" :value="__('Vùng')" />
                            <div class="flex gap-4 mt-2">
                                @foreach ([1,2,3,4] as $vung)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="region" value="{{ $vung }}" class="form-radio text-green-600" {{ old('region', 1) == $vung ? 'checked' : '' }} required>
                                        <span class="ml-2">{{ $vung }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label :value="__('Mức lương đóng bảo hiểm')" />
                            <div class="flex gap-4 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="insurance_salary_type" value="official" class="form-radio text-green-600" {{ old('insurance_salary_type', 'official') == 'official' ? 'checked' : '' }} onclick="toggleInsuranceSalaryInput()" required>
                                    <span class="ml-2">Trên lương chính thức</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="insurance_salary_type" value="custom" class="form-radio text-green-600" {{ old('insurance_salary_type') == 'custom' ? 'checked' : '' }} onclick="toggleInsuranceSalaryInput()">
                                    <span class="ml-2">Khác:</span>
                                    <input type="number" name="insurance_salary_custom" id="insurance_salary_custom" class="ml-2 border-gray-300 rounded-md shadow-sm py-1 px-2 w-32" min="0" placeholder="VNĐ" value="{{ old('insurance_salary_custom') }}" style="display: {{ old('insurance_salary_type') == 'custom' ? 'inline-block' : 'none' }};" />
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="dependents" :value="__('Số người phụ thuộc')" />
                            <x-text-input id="dependents" class="block mt-1 w-full" type="number" name="dependents" :value="old('dependents', 0)" min="0" required placeholder="Số người phụ thuộc hợp lệ" />
                        </div>

                        <div class="mb-4" id="gross_income_field">
                            <x-input-label for="gross_income" :value="__('Tổng thu nhập Gross (VNĐ)')" />
                            <x-text-input id="gross_income" class="block mt-1 w-full" type="number" step="1" name="gross_income" :value="old('gross_income')" min="0" placeholder="Tổng tiền trước thuế và các khoản khấu trừ" />
                            <x-input-error :messages="$errors->get('gross_income')" class="mt-2" />
                        </div>

                        <div class="mb-4" id="net_income_field" style="display: none;">
                            <x-input-label for="net_income" :value="__('Thu nhập Net (thực nhận)')" />
                            <x-text-input id="net_income" class="block mt-1 w-full" type="number" step="1" name="net_income" :value="old('net_income')" min="0" placeholder="Số tiền thực nhận sau các khoản khấu trừ" />
                            <x-input-error :messages="$errors->get('net_income')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="tax_paid" :value="__('Thuế TNCN đã nộp (tạm tính, tùy chọn)')" />
                            <x-text-input id="tax_paid" class="block mt-1 w-full" type="number" step="1" name="tax_paid" :value="old('tax_paid')" min="0" placeholder="Số tiền thuế TNCN đã nộp tạm tính" />
                            <x-input-error :messages="$errors->get('tax_paid')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="bhxh_deduction" :value="__('Khoản khấu trừ BHXH (tùy chọn)')" />
                            <x-text-input id="bhxh_deduction" class="block mt-1 w-full" type="number" step="1" name="bhxh_deduction" :value="old('bhxh_deduction')" min="0" placeholder="Khoản khấu trừ đóng BHXH, BHYT, BHTN" />
                            <x-input-error :messages="$errors->get('bhxh_deduction')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="other_deductions" :value="__('Các khoản giảm trừ khác (tùy chọn)')" />
                            <x-text-input id="other_deductions" class="block mt-1 w-full" type="number" step="1" name="other_deductions" :value="old('other_deductions')" min="0" placeholder="Các khoản giảm trừ khác chưa được đề cập" />
                            <x-input-error :messages="$errors->get('other_deductions')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                <i class="fa-solid fa-save mr-2"></i> {{ __('Lưu Khoản Thu Nhập') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        function toggleDirectionFields() {
            var direction = document.getElementById('calculation_direction').value;
            var grossField = document.getElementById('gross_income_field');
            var netField = document.getElementById('net_income_field');
            if (direction === 'gross_to_net') {
                grossField.style.display = 'block';
                netField.style.display = 'none';
            } else {
                grossField.style.display = 'none';
                netField.style.display = 'block';
            }
        }

        function toggleInsuranceSalaryInput() {
            var customInput = document.getElementById('insurance_salary_custom');
            var customRadio = document.querySelector('input[name=insurance_salary_type][value=custom]');
            if (customRadio.checked) {
                customInput.style.display = 'inline-block';
            } else {
                customInput.style.display = 'none';
                customInput.value = '';
            }
        }

        // Call the function on page load to handle old('entry_type')
        document.addEventListener('DOMContentLoaded', function() {
            toggleMonthField();
            toggleDirectionFields();
            toggleInsuranceSalaryInput();
            document.getElementById('calculation_direction').addEventListener('change', toggleDirectionFields);
            var radios = document.querySelectorAll('input[name=insurance_salary_type]');
            radios.forEach(function(radio) {
                radio.addEventListener('change', toggleInsuranceSalaryInput);
            });
        });
    </script>
</x-app-layout>