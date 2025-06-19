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

                    <form method="POST" action="{{ route('income-entries.store') }}" class="bg-white p-8 rounded-lg shadow-md max-w-xl mx-auto mt-8">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="income_source_id" :value="__('Nguồn thu nhập')" />
                                <select id="income_source_id" name="income_source_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required>
                                    <option value="">Chọn nguồn thu nhập</option>
                                    @foreach ($incomeSources as $source)
                                        <option value="{{ $source->id }}" {{ old('income_source_id', request('income_source_id')) == $source->id ? 'selected' : '' }}>
                                            {{ $source->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="month" :value="__('Tháng')" />
                                    <select id="month" name="month" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required>
                                        @for($i=1;$i<=12;$i++)
                                            <option value="{{ $i }}" {{ old('month', request('month', date('n'))) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="year" :value="__('Năm')" />
                                    <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year', request('year', date('Y')))" min="2000" max="{{ date('Y')+1 }}" required placeholder="Năm" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="calculation_direction" :value="__('Chiều tính lương')" />
                                <select id="calculation_direction" name="calculation_direction" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3" required onchange="toggleDirectionFields()">
                                    <option value="gross_to_net" {{ old('calculation_direction', request('calculation_direction', 'gross_to_net')) == 'gross_to_net' ? 'selected' : '' }}>Gross → Net</option>
                                    <option value="net_to_gross" {{ old('calculation_direction', request('calculation_direction')) == 'net_to_gross' ? 'selected' : '' }}>Net → Gross</option>
                                </select>
                            </div>
                            <div id="gross_income_field">
                                <x-input-label for="gross_income" :value="__('Lương Gross (VNĐ)')" />
                                <x-text-input id="gross_income" class="block mt-1 w-full" type="text" inputmode="numeric" pattern="[0-9,]*" name="gross_income" :value="old('gross_income', request('gross_income'))" min="0" placeholder="Nhập lương Gross" />
                            </div>
                            <div id="net_income_field" style="display: none;">
                                <x-input-label for="net_income" :value="__('Lương Net (VNĐ)')" />
                                <x-text-input id="net_income" class="block mt-1 w-full" type="text" inputmode="numeric" pattern="[0-9,]*" name="net_income" :value="old('net_income', request('net_income'))" min="0" placeholder="Nhập lương Net" />
                            </div>
                            <div>
                                <x-input-label :value="__('Mức lương đóng bảo hiểm')" />
                                <div class="flex gap-4 mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="insurance_salary_type" value="official" class="form-radio text-green-600" {{ old('insurance_salary_type', request('insurance_salary_type', 'official')) == 'official' ? 'checked' : '' }} onclick="toggleInsuranceSalaryInput()" required>
                                        <span class="ml-2">Trên lương chính thức</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="insurance_salary_type" value="custom" class="form-radio text-green-600" {{ old('insurance_salary_type', request('insurance_salary_type')) == 'custom' ? 'checked' : '' }} onclick="toggleInsuranceSalaryInput()">
                                        <span class="ml-2">Khác:</span>
                                        <input type="text" name="insurance_salary_custom" id="insurance_salary_custom" class="ml-2 border-gray-300 rounded-md shadow-sm py-1 px-2 w-32" min="0" placeholder="VNĐ" value="{{ old('insurance_salary_custom', request('insurance_salary_custom')) }}" style="display: {{ old('insurance_salary_type', request('insurance_salary_type')) == 'custom' ? 'inline-block' : 'none' }};" inputmode="numeric" pattern="[0-9,]*" />
                                    </label>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="region" :value="__('Vùng')" />
                                <div class="flex gap-4 mt-2">
                                    @foreach ([1,2,3,4] as $vung)
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="region" value="{{ $vung }}" class="form-radio text-indigo-600" {{ old('region', request('region', 1)) == $vung ? 'checked' : '' }} required>
                                            <span class="ml-2">{{ $vung }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @php
                                $dependentsCount = Auth::user()->dependents()->where('status', 'active')->count();
                            @endphp
                            <div>
                                <x-input-label for="dependents" :value="__('Số người phụ thuộc hợp lệ (tự động)')" />
                                <div class="block mt-1 w-full bg-gray-100 border border-gray-200 rounded-md px-3 py-2 text-gray-700">
                                    {{ $dependentsCount }} người
                                </div>
                                <input type="hidden" name="dependents" value="{{ $dependentsCount }}" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ml-4 px-8 py-3 text-lg">
                                    <i class="fa-solid fa-calculator mr-2"></i> {{ __('Tính lương') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($error) && $error)
        <div class="max-w-xl mx-auto mt-6 mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ $error }}
        </div>
    @endif

    @if(isset($result) && is_array($result) && empty($error))
        <div class="mt-8">
            {{-- Bảng tổng hợp --}}
            <h3 class="font-semibold text-lg mb-2 text-green-700">Kết quả</h3>
            <table class="min-w-full mb-4">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Lương Gross</th>
                        <th class="px-4 py-2">Bảo hiểm</th>
                        <th class="px-4 py-2">Thuế TNCN</th>
                        <th class="px-4 py-2">Lương Net</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2">{{ number_format($result['actual_gross_income']) }}</td>
                        <td class="px-4 py-2">- {{ number_format($result['actual_bhxh_deduction']) }}</td>
                        <td class="px-4 py-2">- {{ number_format($result['actual_tax_paid']) }}</td>
                        <td class="px-4 py-2">{{ number_format($result['actual_net_income']) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Diễn giải chi tiết --}}
            <h4 class="font-semibold text-green-700 mb-2">Diễn giải chi tiết (VND)</h4>
            <table class="min-w-full mb-4">
                <tbody>
                    <tr><td class="px-4 py-2">Lương GROSS</td><td class="px-4 py-2 text-right">{{ number_format($result['actual_gross_income']) }}</td></tr>
                    <tr><td class="px-4 py-2">Bảo hiểm xã hội (8%)</td><td class="px-4 py-2 text-right">-{{ number_format($result['bhxh']) }}</td></tr>
                    <tr><td class="px-4 py-2">Bảo hiểm y tế (1.5%)</td><td class="px-4 py-2 text-right">-{{ number_format($result['bhyt']) }}</td></tr>
                    <tr><td class="px-4 py-2">Bảo hiểm thất nghiệp (1%)</td><td class="px-4 py-2 text-right">-{{ number_format($result['bhtn']) }}</td></tr>
                    <tr><td class="px-4 py-2">Thu nhập trước thuế</td><td class="px-4 py-2 text-right">{{ number_format($result['thu_nhap_truoc_thue']) }}</td></tr>
                    <tr><td class="px-4 py-2">Giảm trừ bản thân</td><td class="px-4 py-2 text-right">-{{ number_format($result['giam_tru_ban_than']) }}</td></tr>
                    <tr><td class="px-4 py-2">Giảm trừ người phụ thuộc</td><td class="px-4 py-2 text-right">-{{ number_format($result['giam_tru_phu_thuoc']) }}</td></tr>
                    <tr><td class="px-4 py-2">Thu nhập chịu thuế</td><td class="px-4 py-2 text-right">{{ number_format($result['thu_nhap_chiu_thue']) }}</td></tr>
                    <tr><td class="px-4 py-2">Thuế thu nhập cá nhân</td><td class="px-4 py-2 text-right">-{{ number_format($result['actual_tax_paid']) }}</td></tr>
                    <tr><td class="px-4 py-2 font-semibold">Lương NET</td><td class="px-4 py-2 text-right font-semibold">{{ number_format($result['actual_net_income']) }}</td></tr>
                </tbody>
            </table>

            {{-- Chi tiết thuế TNCN từng bậc --}}
            <h4 class="font-semibold text-green-700 mb-2">(*) Chi tiết thuế thu nhập cá nhân (VND)</h4>
            <table class="min-w-full mb-4">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Mức chịu thuế</th>
                        <th class="px-4 py-2">Thuế suất</th>
                        <th class="px-4 py-2">Tiền nộp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result['tax_brackets_detail'] ?? [] as $row)
                        <tr>
                            <td class="px-4 py-2">{{ $row['label'] }}</td>
                            <td class="px-4 py-2">{{ $row['rate'] }}%</td>
                            <td class="px-4 py-2 text-right">{{ number_format($row['amount']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Chi phí người sử dụng lao động --}}
            <h4 class="font-semibold text-green-700 mb-2">Người sử dụng lao động trả (VND)</h4>
            <table class="min-w-full mb-4">
                <tbody>
                    <tr><td class="px-4 py-2">Lương GROSS</td><td class="px-4 py-2 text-right">{{ number_format($result['actual_gross_income']) }}</td></tr>
                    <tr><td class="px-4 py-2">Bảo hiểm xã hội (17%)</td><td class="px-4 py-2 text-right">{{ number_format($result['bhxh_employer']) }}</td></tr>
                    <tr><td class="px-4 py-2">Bảo hiểm Tai nạn lao động - Bệnh nghề nghiệp (0.5%)</td><td class="px-4 py-2 text-right">{{ number_format($result['bhtnld']) }}</td></tr>
                    <tr><td class="px-4 py-2">Bảo hiểm y tế (3%)</td><td class="px-4 py-2 text-right">{{ number_format($result['bhyt_employer']) }}</td></tr>
                    <tr><td class="px-4 py-2">Bảo hiểm thất nghiệp (1%)</td><td class="px-4 py-2 text-right">{{ number_format($result['bhtn_employer']) }}</td></tr>
                    <tr><td class="px-4 py-2 font-semibold">Tổng cộng</td><td class="px-4 py-2 text-right font-semibold">{{ number_format($result['tong_chi_phi']) }}</td></tr>
                </tbody>
            </table>
        </div>
    @endif

    @if(isset($history) && $history->count())
        <div class="mt-10">
            <h3 class="font-semibold text-lg mb-4 text-indigo-700">Lịch sử các khoản thu nhập đã lưu</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-2">Tháng/Năm</th>
                            <th class="px-4 py-2">Nguồn thu nhập</th>
                            <th class="px-4 py-2">Lương Gross</th>
                            <th class="px-4 py-2">Lương Net</th>
                            <th class="px-4 py-2">Bảo hiểm</th>
                            <th class="px-4 py-2">Thuế TNCN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item->month }}/{{ $item->year }}</td>
                                <td class="px-4 py-2">{{ $item->incomeSource->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ number_format($item->gross_income) }} đ</td>
                                <td class="px-4 py-2">{{ number_format($item->net_income) }} đ</td>
                                <td class="px-4 py-2">{{ number_format($item->bhxh_deduction) }} đ</td>
                                <td class="px-4 py-2">{{ number_format($item->tax_paid) }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
            toggleDirectionFields();
            toggleInsuranceSalaryInput();
            document.getElementById('calculation_direction').addEventListener('change', toggleDirectionFields);
            var radios = document.querySelectorAll('input[name=insurance_salary_type]');
            radios.forEach(function(radio) {
                radio.addEventListener('change', toggleInsuranceSalaryInput);
            });
        });

        function formatNumberInput(input) {
            let value = input.value.replace(/,/g, '');
            if (!isNaN(value) && value !== '') {
                input.value = Number(value).toLocaleString('en-US');
            }
        }
        function unformatNumberInput(input) {
            input.value = input.value.replace(/,/g, '');
        }
        document.addEventListener('DOMContentLoaded', function() {
            const moneyInputs = [
                'gross_income',
                'net_income',
                'insurance_salary_custom',
                'bhxh_deduction',
                'other_deductions',
                'tax_paid'
            ];
            moneyInputs.forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', function() { formatNumberInput(el); });
                    el.addEventListener('blur', function() { formatNumberInput(el); });
                }
            });
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    moneyInputs.forEach(function(id) {
                        const el = document.getElementById(id);
                        if (el) unformatNumberInput(el);
                    });
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const moneyInputs = [
                'gross_income',
                'net_income',
                'insurance_salary_custom',
                'bhxh_deduction',
                'other_deductions',
                'tax_paid'
            ];
            moneyInputs.forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    new Cleave(el, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand',
                        numeralDecimalMark: '.',
                        delimiter: ','
                    });
                }
            });
        });
    </script>
</x-app-layout>