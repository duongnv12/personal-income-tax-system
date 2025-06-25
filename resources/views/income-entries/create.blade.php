<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-sack-dollar mr-2 text-green-600"></i> {{ __('Thêm Khoản Thu Nhập Mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="w-full max-w-2xl bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 flex items-center">
                            <i class="fa-solid fa-plus-circle mr-2 text-green-600"></i> {{ __('Thêm Khoản Thu Nhập Mới') }}
                        </h3>

                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
                            </div>
                        @endif
                        
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <p class="font-bold">Vui lòng kiểm tra lại các lỗi sau:</p>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('income-entries.store') }}">
                            @csrf
                            <div class="grid grid-cols-1 gap-6">
                                {{-- Chọn nguồn thu nhập --}}
                                <div>
                                    <x-input-label for="income_source_id" :value="__('Nguồn thu nhập')" />
                                    <select id="income_source_id" name="income_source_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required>
                                        <option value="">Chọn nguồn thu nhập</option>
                                        @foreach ($incomeSources as $source)
                                            <option value="{{ $source->id }}" {{ (old('income_source_id', request('income_source_id')) ?? ($oldInput['income_source_id'] ?? '')) == $source->id ? 'selected' : '' }}>
                                                {{ $source->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Loại nhập: Hàng tháng / Cả năm --}}
                                <div>
                                    <x-input-label for="entry_type" :value="__('Loại nhập')" />
                                    <div class="flex gap-4 mt-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="entry_type" value="monthly" class="form-radio text-indigo-600"
                                                {{ old('entry_type', request('entry_type', 'monthly')) == 'monthly' ? 'checked' : '' }} onclick="toggleEntryType()" required>
                                            <span class="ml-2">Hàng tháng</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="entry_type" value="yearly" class="form-radio text-indigo-600"
                                                {{ old('entry_type', request('entry_type')) == 'yearly' ? 'checked' : '' }} onclick="toggleEntryType()" required>
                                            <span class="ml-2">Cả năm</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Tháng & Năm --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div id="month_field">
                                        <x-input-label for="month" :value="__('Tháng')" />
                                        <select id="month" name="month" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3">
                                            @for($i=1;$i<=12;$i++)
                                                <option value="{{ $i }}" {{ (old('month', request('month', date('n'))) ?? ($oldInput['month'] ?? date('n'))) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="year" :value="__('Năm')" />
                                        <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year', request('year', date('Y'))) ?? ($oldInput['year'] ?? date('Y'))" min="2000" max="{{ date('Y')+1 }}" required placeholder="Năm" />
                                    </div>
                                </div>

                                {{-- Chiều tính lương --}}
                                <div>
                                    <x-input-label for="calculation_direction" :value="__('Chiều tính lương')" />
                                    <div class="mt-2 grid grid-cols-2 gap-3">
                                         <label for="gross_to_net" class="relative flex items-center justify-center rounded-md border py-3 px-3 text-sm font-semibold uppercase sm:flex-1 cursor-pointer focus:outline-none">
                                            <input type="radio" name="calculation_direction" value="gross_to_net" id="gross_to_net" class="sr-only" aria-labelledby="gross_to_net-label" onchange="toggleDirectionFields()" {{ (old('calculation_direction', request('calculation_direction', 'gross_to_net')) ?? ($oldInput['calculation_direction'] ?? '')) == 'gross_to_net' ? 'checked' : '' }}>
                                            <span id="gross_to_net-label">Lương Gross</span>
                                            <span class="pointer-events-none absolute -inset-px rounded-md border-2" aria-hidden="true"></span>
                                        </label>
                                        <label for="net_to_gross" class="relative flex items-center justify-center rounded-md border py-3 px-3 text-sm font-semibold uppercase sm:flex-1 cursor-pointer focus:outline-none">
                                            <input type="radio" name="calculation_direction" value="net_to_gross" id="net_to_gross" class="sr-only" aria-labelledby="net_to_gross-label" onchange="toggleDirectionFields()" {{ (old('calculation_direction', request('calculation_direction')) ?? ($oldInput['calculation_direction'] ?? '')) == 'net_to_gross' ? 'checked' : '' }}>
                                            <span id="net_to_gross-label">Lương Net</span>
                                            <span class="pointer-events-none absolute -inset-px rounded-md border-2" aria-hidden="true"></span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Lương Gross/Net --}}
                                <div id="gross_income_field">
                                    <x-input-label for="gross_income" :value="__('Lương Gross (VNĐ)')" />
                                    <x-text-input id="gross_income" class="block mt-1 w-full" type="text" inputmode="numeric" name="gross_income" :value="old('gross_income', request('gross_income')) ?? (isset($oldInput['gross_income']) ? number_format($oldInput['gross_income']) : '')" min="0" placeholder="Nhập lương Gross" />
                                    <p class="text-sm text-gray-500 mt-1" id="gross_income_hint">Nhập lương Gross cho 1 tháng</p>
                                </div>
                                <div id="net_income_field" style="display: none;">
                                    <x-input-label for="net_income" :value="__('Lương Net (VNĐ)')" />
                                    <x-text-input id="net_income" class="block mt-1 w-full" type="text" inputmode="numeric" name="net_income" :value="old('net_income', request('net_income')) ?? (isset($oldInput['net_income']) ? number_format($oldInput['net_income']) : '')" min="0" placeholder="Nhập lương Net" />
                                    <p class="text-sm text-gray-500 mt-1" id="net_income_hint">Nhập lương Net cho 1 tháng</p>
                                </div>

                                {{-- Mức lương đóng bảo hiểm --}}
                                <div>
                                    <x-input-label :value="__('Mức lương đóng bảo hiểm')" />
                                    <div class="flex gap-4 mt-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="insurance_salary_type" value="official" class="form-radio text-indigo-600" {{ (old('insurance_salary_type', request('insurance_salary_type', 'official')) ?? ($oldInput['insurance_salary_type'] ?? 'official')) == 'official' ? 'checked' : '' }} onclick="toggleInsuranceSalaryInput()" required>
                                            <span class="ml-2">Trên lương chính thức</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="insurance_salary_type" value="custom" class="form-radio text-indigo-600" {{ (old('insurance_salary_type', request('insurance_salary_type')) ?? ($oldInput['insurance_salary_type'] ?? '')) == 'custom' ? 'checked' : '' }} onclick="toggleInsuranceSalaryInput()">
                                            <span class="ml-2">Khác</span>
                                        </label>
                                    </div>
                                     <x-text-input type="text" name="insurance_salary_custom" id="insurance_salary_custom" class="mt-1 border-gray-300 rounded-md shadow-sm py-1 px-2 w-full" min="0" placeholder="Nhập mức lương đóng BH" :value="old('insurance_salary_custom', request('insurance_salary_custom')) ?? (isset($oldInput['insurance_salary_custom']) ? number_format($oldInput['insurance_salary_custom']) : '')" style="display: {{ (old('insurance_salary_type', request('insurance_salary_type')) ?? ($oldInput['insurance_salary_type'] ?? '')) == 'custom' ? 'block' : 'none' }};" inputmode="numeric" />
                                </div>

                                {{-- Vùng --}}
                                <div>
                                    <x-input-label for="region" :value="__('Vùng')" />
                                    <div class="flex flex-wrap gap-4 mt-2">
                                        @foreach ([1,2,3,4] as $vung)
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="region" value="{{ $vung }}" class="form-radio text-indigo-600" {{ (old('region', request('region', 1)) ?? ($oldInput['region'] ?? 1)) == $vung ? 'checked' : '' }} required>
                                                <span class="ml-2">Vùng {{ $vung }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                {{-- Số người phụ thuộc --}}
                                <div>
                                    <x-input-label for="dependents" :value="__('Số người phụ thuộc')" />
                                    <x-text-input id="dependents" class="block mt-1 w-full" type="number" name="dependents" :value="old('dependents', $dependentCount ?? 0)" min="0" required />
                                    <x-input-error :messages="$errors->get('dependents')" class="mt-2" />
                                    <p class="text-sm text-gray-500 mt-1">Nhập số người phụ thuộc bạn muốn tính giảm trừ cho khoản thu nhập này.</p>
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button class="ml-4 px-8 py-3 text-lg w-full justify-center">
                                        <i class="fa-solid fa-calculator mr-2"></i> {{ __('Tính lương & Lưu') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($result) && is_array($result) && empty($error))
    <x-modal name="calculation-result" :show="true" maxWidth="2xl" focusable>
        <div class="p-6">
            <div class="flex justify-between items-start">
                <h2 class="text-2xl font-bold text-gray-900">
                    <i class="fa-solid fa-calculator mr-2 text-green-600"></i> Kết quả tính lương
                </h2>
                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-6">
                <h3 class="font-semibold text-lg mb-2 text-gray-800">
                    Bảng tóm tắt
                    @if(isset($result['entry_type']) && $result['entry_type'] === 'yearly')
                        <span class="text-sm text-blue-600 font-normal">(Tính cho cả năm)</span>
                    @else
                        <span class="text-sm text-green-600 font-normal">(Tính cho 1 tháng)</span>
                    @endif
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center p-4 bg-gray-50 rounded-lg border">
                    <div>
                        <p class="text-sm text-gray-600">Lương Gross</p>
                        <p class="font-bold text-lg text-gray-800">{{ number_format($result['actual_gross_income']) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Bảo hiểm</p>
                        <p class="font-bold text-lg text-red-600">-{{ number_format($result['actual_bhxh_deduction']) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Thuế TNCN</p>
                        <p class="font-bold text-lg text-red-600">-{{ number_format($result['actual_tax_paid']) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lương Net</p>
                        <p class="font-bold text-lg text-green-700">{{ number_format($result['actual_net_income']) }}</p>
                    </div>
                </div>

                <h4 class="font-semibold text-gray-800 mt-6 mb-2">
                    Diễn giải chi tiết (VND)
                    @if(isset($result['entry_type']) && $result['entry_type'] === 'yearly')
                        <span class="text-sm text-blue-600 font-normal"> - Cả năm</span>
                    @else
                        <span class="text-sm text-green-600 font-normal"> - 1 tháng</span>
                    @endif
                </h4>
                <div class="border rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-200">
                            <tr class="bg-white"><td class="px-4 py-2">Lương GROSS</td><td class="px-4 py-2 text-right font-semibold">{{ number_format($result['actual_gross_income']) }}</td></tr>
                            <tr class="bg-gray-50"><td class="px-4 py-2 pl-6">Bảo hiểm xã hội (8%)</td><td class="px-4 py-2 text-right">-{{ number_format($result['bhxh']) }}</td></tr>
                            <tr class="bg-white"><td class="px-4 py-2 pl-6">Bảo hiểm y tế (1.5%)</td><td class="px-4 py-2 text-right">-{{ number_format($result['bhyt']) }}</td></tr>
                            <tr class="bg-gray-50"><td class="px-4 py-2 pl-6">Bảo hiểm thất nghiệp (1%)</td><td class="px-4 py-2 text-right">-{{ number_format($result['bhtn']) }}</td></tr>
                            <tr class="bg-white"><td class="px-4 py-2">Thu nhập trước thuế</td><td class="px-4 py-2 text-right font-semibold">{{ number_format($result['thu_nhap_truoc_thue']) }}</td></tr>
                            <tr class="bg-gray-50"><td class="px-4 py-2">Giảm trừ bản thân</td><td class="px-4 py-2 text-right">-{{ number_format($result['giam_tru_ban_than']) }}</td></tr>
                            <tr class="bg-white"><td class="px-4 py-2">Giảm trừ người phụ thuộc</td><td class="px-4 py-2 text-right">-{{ number_format($result['giam_tru_phu_thuoc']) }}</td></tr>
                            <tr class="bg-gray-50"><td class="px-4 py-2 font-bold">Thu nhập tính thuế</td><td class="px-4 py-2 text-right font-bold">{{ number_format($result['thu_nhap_chiu_thue']) }}</td></tr>
                            <tr class="bg-white"><td class="px-4 py-2">Thuế thu nhập cá nhân (*)</td><td class="px-4 py-2 text-right font-semibold text-red-600">-{{ number_format($result['actual_tax_paid']) }}</td></tr>
                            <tr class="bg-green-100"><td class="px-4 py-2 font-extrabold text-green-800">Lương NET (Thực nhận)</td><td class="px-4 py-2 text-right font-extrabold text-lg text-green-800">{{ number_format($result['actual_net_income']) }}</td></tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="font-semibold text-gray-800 mt-6 mb-2">(*) Chi tiết thuế thu nhập cá nhân (VND)</h4>
                <div class="border rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Mức chịu thuế</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Thuế suất</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">Tiền nộp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($result['tax_brackets_detail'] ?? [] as $row)
                                <tr>
                                    <td class="px-4 py-2">{{ $row['label'] }}</td>
                                    <td class="px-4 py-2">{{ $row['rate'] }}%</td>
                                    <td class="px-4 py-2 text-right">{{ number_format($row['amount']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex justify-end bg-gray-50 p-4 rounded-b-lg">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Đóng
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateRadioStyles() {
                document.querySelectorAll('input[name="calculation_direction"]').forEach((radio) => {
                    const label = radio.parentElement;
                    if (radio.checked) {
                        label.classList.add('border-indigo-600', 'ring-2', 'ring-indigo-600');
                        label.classList.remove('border-gray-300');
                    } else {
                        label.classList.remove('border-indigo-600', 'ring-2', 'ring-indigo-600');
                        label.classList.add('border-gray-300');
                    }
                });
            }
        
            function toggleDirectionFields() {
                var direction = document.querySelector('input[name="calculation_direction"]:checked').value;
                var grossField = document.getElementById('gross_income_field');
                var netField = document.getElementById('net_income_field');
                if (direction === 'gross_to_net') {
                    grossField.style.display = 'block';
                    netField.style.display = 'none';
                } else {
                    grossField.style.display = 'none';
                    netField.style.display = 'block';
                }
                updateRadioStyles();
            }

            function toggleInsuranceSalaryInput() {
                var customInput = document.getElementById('insurance_salary_custom');
                var customRadio = document.querySelector('input[name=insurance_salary_type][value=custom]');
                if (customRadio.checked) {
                    customInput.style.display = 'block';
                } else {
                    customInput.style.display = 'none';
                    customInput.value = '';
                }
            }

            function toggleEntryType() {
                var entryType = document.querySelector('input[name="entry_type"]:checked').value;
                var monthField = document.getElementById('month_field');
                var grossHint = document.getElementById('gross_income_hint');
                var netHint = document.getElementById('net_income_hint');
                
                if (entryType === 'yearly') {
                    monthField.style.display = 'none';
                    document.getElementById('month').value = '';
                    if (grossHint) grossHint.textContent = 'Nhập tổng lương Gross cho cả năm';
                    if (netHint) netHint.textContent = 'Nhập tổng lương Net cho cả năm';
                } else {
                    monthField.style.display = 'block';
                    if (grossHint) grossHint.textContent = 'Nhập lương Gross cho 1 tháng';
                    if (netHint) netHint.textContent = 'Nhập lương Net cho 1 tháng';
                }
            }

            toggleDirectionFields();
            toggleInsuranceSalaryInput();
            toggleEntryType();
            document.querySelectorAll('input[name=insurance_salary_type]').forEach(function(radio) {
                radio.addEventListener('change', toggleInsuranceSalaryInput);
            });
             document.querySelectorAll('input[name="calculation_direction"]').forEach((radio) => {
                radio.addEventListener('change', toggleDirectionFields);
            });
            
             document.querySelectorAll('input[name="entry_type"]').forEach(function(radio) {
                radio.addEventListener('change', toggleEntryType);
            });
            
             new Cleave('#gross_income', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });

            new Cleave('#net_income', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
            
            new Cleave('#insurance_salary_custom', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });

            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    ['gross_income', 'net_income', 'insurance_salary_custom'].forEach(function(id) {
                        const el = document.getElementById(id);
                        if (el) {
                           // Unformat the value before submitting
                           el.value = el.value.replace(/,/g, '');
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>