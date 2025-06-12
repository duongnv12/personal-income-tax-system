<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-file-invoice-dollar mr-2 text-blue-600"></i> {{ __('Báo cáo Quyết toán Thuế TNCN') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> {{-- shadow-xl để nổi bật hơn --}}
                <div class="p-6 text-gray-900">

                    <div class="mb-8 flex flex-col md:flex-row items-center justify-between border-b pb-4">
                        <h3 class="text-2xl font-extrabold text-gray-800 mb-4 md:mb-0">
                            Quyết toán thuế TNCN năm <span class="text-blue-700">{{ $selectedYear }}</span>
                        </h3>
                        <div class="flex flex-col md:flex-row items-center space-y-3 md:space-y-0 md:space-x-4">
                            <label for="report_year" class="text-gray-700 font-semibold flex items-center mr-2">
                                <i class="fa-solid fa-calendar-alt mr-2 text-blue-500"></i> Năm:
                            </label>
                            <select id="report_year"
                                onchange="window.location.href = this.value"
                                class="border border-blue-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-base py-1 px-3 bg-white font-semibold text-blue-700 transition mr-2"
                                style="background-image: none; min-width: 100px;">
                                @foreach ($availableYears as $year)
                                    <option value="{{ route('tax.yearly_settlement', $year) }}"
                                        {{ $year == $selectedYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('tax.yearly_settlement.export_pdf', $selectedYear) }}"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <i class="fa-solid fa-file-pdf mr-2"></i> Xuất PDF
                            </a>
                            <a href="{{ route('tax.yearly_settlement.export_excel', $selectedYear) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <i class="fa-solid fa-file-excel mr-2"></i> Xuất Excel
                            </a>
                        </div>
                    </div>

                    {{-- Summary Cards --}}
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fa-solid fa-chart-line mr-2 text-blue-500"></i> Tổng quan tài chính
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        {{-- Card: Tổng thu nhập Gross chịu thuế --}}
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-blue-600 text-3xl">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng thu nhập Gross chịu thuế:</p>
                                <p class="font-bold text-2xl text-blue-900 mt-1">{{ number_format($yearlyTaxSettlement['total_gross_income'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        {{-- Card: Tổng giảm trừ BHXH & khác --}}
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-purple-600 text-3xl">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng giảm trừ BHXH & khác:</p>
                                <p class="font-bold text-2xl text-purple-900 mt-1">{{ number_format($yearlyTaxSettlement['total_bhxh_deduction'] + $yearlyTaxSettlement['total_other_deductions'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        {{-- Card: Tổng giảm trừ gia cảnh --}}
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-indigo-600 text-3xl">
                                <i class="fa-solid fa-users-line"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng giảm trừ gia cảnh:</p>
                                <p class="font-bold text-2xl text-indigo-900 mt-1">{{ number_format($yearlyTaxSettlement['total_personal_deductions'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        {{-- Card: Thu nhập tính thuế cả năm --}}
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-yellow-600 text-3xl">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Thu nhập tính thuế cả năm:</p>
                                <p class="font-bold text-2xl text-yellow-900 mt-1">{{ number_format($yearlyTaxSettlement['total_taxable_income_yearly'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        {{-- Card: Tổng thuế đã tạm nộp --}}
                        <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-red-600 text-3xl">
                                <i class="fa-solid fa-money-check-dollar"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng thuế đã tạm nộp:</p>
                                <p class="font-bold text-2xl text-red-900 mt-1">{{ number_format($yearlyTaxSettlement['total_tax_paid_provisional'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        {{-- Card: Tổng thuế phải nộp cả năm --}}
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-green-600 text-3xl">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng thuế phải nộp cả năm:</p>
                                <p class="font-bold text-2xl text-green-900 mt-1">{{ number_format($yearlyTaxSettlement['total_tax_required_yearly'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                    </div>

                    {{-- Final Tax Status --}}
                    <div class="mb-6
                        @if ($yearlyTaxSettlement['tax_to_pay_or_refund'] > 0)
                            bg-red-100 text-red-800 border-red-300
                        @elseif ($yearlyTaxSettlement['tax_to_pay_or_refund'] < 0)
                            bg-green-100 text-green-800 border-green-300
                        @else
                            bg-blue-100 text-blue-800 border-blue-300
                        @endif
                        p-6 rounded-lg font-extrabold text-xl md:text-2xl text-center border-l-4 shadow-md transition-all duration-300 transform hover:scale-[1.01]">
                        @if ($yearlyTaxSettlement['tax_to_pay_or_refund'] > 0)
                            <i class="fa-solid fa-circle-exclamation mr-3"></i> Số thuế còn phải nộp: <span class="text-red-900">{{ number_format($yearlyTaxSettlement['tax_to_pay_or_refund'], 0, ',', '.') }} VNĐ</span>
                        @elseif ($yearlyTaxSettlement['tax_to_pay_or_refund'] < 0)
                            <i class="fa-solid fa-circle-check mr-3"></i> Số thuế được hoàn lại: <span class="text-green-900">{{ number_format(abs($yearlyTaxSettlement['tax_to_pay_or_refund']), 0, ',', '.') }} VNĐ</span>
                        @else
                            <i class="fa-solid fa-circle-info mr-3"></i> Bạn không có số thuế phải nộp thêm hoặc được hoàn lại trong năm <span class="font-bold">{{ $selectedYear }}</span>.
                        @endif
                    </div>

                    {{-- Income Details Table --}}
                    <h3 class="text-xl font-bold text-gray-800 mt-10 mb-6 flex items-center border-b pb-3">
                        <i class="fa-solid fa-table mr-2 text-purple-500"></i> Chi tiết các khoản thu nhập trong năm <span class="text-blue-700 ml-2">{{ $selectedYear }}</span>
                    </h3>
                    @php
                        // Lọc các khoản thu nhập cho năm được chọn
                        $incomeEntriesForSelectedYear = Auth::user()->incomeEntries()
                                                               ->where('year', $selectedYear)
                                                               ->with('incomeSource')
                                                               ->orderBy('month')
                                                               ->get();
                    @endphp

                    @if ($incomeEntriesForSelectedYear->isEmpty())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4" role="alert">
                            <p class="font-bold">Không có dữ liệu!</p>
                            <p>Hiện không có khoản thu nhập nào được ghi nhận cho năm {{ $selectedYear }}.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto shadow-md rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tháng</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nguồn</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Loại</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Gross (VNĐ)</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">BHXH (VNĐ)</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Khấu trừ khác (VNĐ)</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thuế tạm nộp (VNĐ)</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Net (VNĐ)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($incomeEntriesForSelectedYear as $entry)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($entry->entry_type === 'monthly')
                                                    Tháng {{ $entry->month }}
                                                @else
                                                    Cả năm
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $entry->incomeSource->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $entry->entry_type === 'monthly' ? 'Hàng tháng' : 'Hàng năm' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-semibold">{{ number_format($entry->gross_income, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($entry->bhxh_deduction ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($entry->other_deductions ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-red-600">{{ number_format($entry->tax_paid ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-green-700">{{ number_format($entry->net_income ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Company Specific PDF Export --}}
                    @php
                        // Lấy danh sách các công ty có thu nhập trong năm
                        $companies = $incomeEntriesForSelectedYear->pluck('incomeSource')->unique('id');
                    @endphp

                    @if ($companies->count())
                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fa-solid fa-building mr-2 text-indigo-500"></i> Xuất báo cáo theo công ty
                            </h3>
                            <p class="text-gray-700 mb-4">Chọn một công ty để xuất báo cáo thu nhập riêng từ nguồn đó (thường dùng để lấy chứng từ khấu trừ thuế):</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($companies as $company)
                                    <a href="{{ route('tax-reports.company-income-pdf', ['year' => $selectedYear, 'companyId' => $company->id]) }}"
                                       class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent rounded-full font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                        <i class="fa-solid fa-file-export mr-2"></i> {{ $company->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>