<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-file-invoice-dollar mr-2 text-blue-600"></i> {{ __('Báo cáo Quyết toán Thuế TNCN') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" x-data="sourceDetailsModal()">
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
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-blue-600 text-3xl"><i class="fa-solid fa-sack-dollar"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng thu nhập Gross chịu thuế:</p>
                                <p class="font-bold text-2xl text-blue-900 mt-1">{{ number_format($yearlyTaxSettlement['total_gross_income'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-purple-600 text-3xl"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng giảm trừ BHXH & khác:</p>
                                <p class="font-bold text-2xl text-purple-900 mt-1">{{ number_format($yearlyTaxSettlement['total_bhxh_deduction'] + $yearlyTaxSettlement['total_other_deductions'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-indigo-600 text-3xl"><i class="fa-solid fa-users-line"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng giảm trừ gia cảnh:</p>
                                <p class="font-bold text-2xl text-indigo-900 mt-1">{{ number_format($yearlyTaxSettlement['total_personal_deductions'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-yellow-600 text-3xl"><i class="fa-solid fa-file-invoice"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Thu nhập tính thuế cả năm:</p>
                                <p class="font-bold text-2xl text-yellow-900 mt-1">{{ number_format($yearlyTaxSettlement['total_taxable_income_yearly'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-red-600 text-3xl"><i class="fa-solid fa-money-check-dollar"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng thuế đã tạm nộp:</p>
                                <p class="font-bold text-2xl text-red-900 mt-1">{{ number_format($yearlyTaxSettlement['total_tax_paid_provisional'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow-md flex items-start space-x-4">
                            <div class="flex-shrink-0 text-green-600 text-3xl"><i class="fa-solid fa-file-circle-check"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tổng thuế phải nộp cả năm:</p>
                                <p class="font-bold text-2xl text-green-900 mt-1">{{ number_format($yearlyTaxSettlement['total_tax_required_yearly'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Bảng tổng hợp theo từng nguồn thu nhập --}}
                    @if(isset($breakdownBySource))
                    <h3 class="text-xl font-bold text-gray-800 mt-10 mb-6 flex items-center border-b pb-3">
                        <i class="fa-solid fa-chart-pie mr-2 text-teal-500"></i>
                        Tổng hợp theo từng Nguồn Thu Nhập
                    </h3>
                    @if ($breakdownBySource->isEmpty())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4" role="alert">
                            <p>Không có dữ liệu nguồn thu nhập để tổng hợp cho năm {{ $selectedYear }}.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto shadow-md rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nguồn thu nhập</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Loại</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Tổng Gross</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thuế tạm nộp</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($breakdownBySource as $sourceId => $source)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $source['source_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                                @switch($source['income_type'])
                                                    @case('salary') Tiền lương, TC @break
                                                    @case('business') Kinh doanh @break
                                                    @case('investment') Đầu tư @break
                                                    @default {{ ucfirst($source['income_type']) }}
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-semibold">{{ number_format($source['total_gross'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-red-600">{{ number_format($source['total_tax_paid'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" @click="fetchSourceDetails({{ $selectedYear }}, {{ $sourceId }})" class="text-indigo-600 hover:text-indigo-900 font-semibold hover:underline">
                                                    Xem chi tiết
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @endif

                    {{-- Final Tax Status --}}
                    <div class="mb-6 mt-10
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
                    
                    {{-- Company Specific PDF Export --}}
                    @php
                        $companies = $breakdownBySource->keys()->map(fn($id) => \App\Models\IncomeSource::find($id))->filter();
                    @endphp

                    @if ($companies->count())
                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fa-solid fa-building mr-2 text-indigo-500"></i> Xuất báo cáo theo công ty
                            </h3>
                            <p class="text-gray-700 mb-4">Chọn một công ty để xuất báo cáo thu nhập riêng từ nguồn đó (thường dùng để lấy chứng từ khấu trừ thuế):</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($companies as $company)
                                    @if ($company)
                                    <a href="{{ route('tax-reports.company-income-pdf', ['year' => $selectedYear, 'companyId' => $company->id]) }}"
                                       class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent rounded-full font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                        <i class="fa-solid fa-file-export mr-2"></i> {{ $company->name }}
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Biểu đồ so sánh thu nhập các năm (di chuyển xuống dưới) --}}
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fa-solid fa-chart-column mr-2 text-green-600"></i> So sánh tổng thu nhập các năm
                    </h3>
                    <canvas id="incomeByYearChart" height="80"></canvas>
                </div>

                <x-modal name="source-details-modal" maxWidth="4xl">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-bold text-gray-900" x-text="`Chi tiết: ${details?.summary?.source_name || '...'}`"></h2>
                            <button @click="$dispatch('close-modal', 'source-details-modal')" class="text-gray-400 hover:text-gray-600">&times;</button>
                        </div>
                
                        <div x-show="isLoading" class="text-center p-10">
                            <p>Đang tải dữ liệu...</p>
                        </div>
                
                        <div x-show="!isLoading && details">
                            {{-- Tóm tắt trong Modal --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="p-3 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border flex items-center space-x-3">
                                    <span class="flex-shrink-0 text-blue-600 text-2xl"><i class="fa-solid fa-sack-dollar"></i></span>
                                    <div>
                                        <p class="text-sm text-gray-600">Tổng Gross</p>
                                        <p class="text-xl font-bold text-gray-800" x-text="details && formatCurrency(details.summary.total_gross)"></p>
                                    </div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border flex items-center space-x-3">
                                    <span class="flex-shrink-0 text-purple-600 text-2xl"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                                    <div>
                                        <p class="text-sm text-gray-600">Tổng BHXH</p>
                                        <p class="text-xl font-bold text-gray-800" x-text="details && formatCurrency(details.summary.total_bhxh)"></p>
                                    </div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-red-50 to-red-100 rounded-lg border flex items-center space-x-3">
                                    <span class="flex-shrink-0 text-red-600 text-2xl"><i class="fa-solid fa-money-check-dollar"></i></span>
                                    <div>
                                        <p class="text-sm text-gray-600">Tổng thuế đã nộp</p>
                                        <p class="text-xl font-bold text-red-600" x-text="details && formatCurrency(details.summary.total_tax_paid)"></p>
                                    </div>
                                </div>
                            </div>
                
                            {{-- Bảng chi tiết tháng trong Modal --}}
                            <div class="max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kỳ</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                <i class="fa-solid fa-money-bill-wave mr-1 text-green-600"></i> Gross income
                                            </th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                <i class="fa-solid fa-shield-halved mr-1 text-blue-600"></i> BHXH
                                            </th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                <i class="fa-solid fa-file-invoice-dollar mr-1 text-red-600"></i> Thuế tạm nộp
                                            </th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                <i class="fa-solid fa-wallet mr-1 text-indigo-600"></i> Net income
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <template x-for="entry in details.entries" :key="entry.id">
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-2" x-text="entry.month ? `Tháng ${entry.month}` : 'Cả năm'"></td>
                                                <td class="px-4 py-2 text-right" x-text="details && formatCurrency(entry.gross_income)"></td>
                                                <td class="px-4 py-2 text-right" x-text="details && formatCurrency(entry.bhxh_deduction)"></td>
                                                <td class="px-4 py-2 text-right" x-text="details && formatCurrency(entry.tax_paid)"></td>
                                                <td class="px-4 py-2 text-right font-semibold" x-text="details && formatCurrency(entry.net_income)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-modal>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function sourceDetailsModal() {
        return {
            isLoading: false,
            details: null,
            async fetchSourceDetails(year, sourceId) {
                this.isLoading = true;
                this.details = null;
                try {
                    const res = await fetch(`/tax-reports/${year}/source/${sourceId}/details`);
                    this.details = await res.json();
                } catch (e) {
                    this.details = null;
                }
                this.isLoading = false;
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'source-details-modal' }));
            },
            formatCurrency(val) {
                if (val == null) return '-';
                return Number(val).toLocaleString('vi-VN');
            }
        }
    }

    // Dữ liệu từ backend
    const incomeByYear = @json($incomeByYear);
    const taxPaidByYear = @json($taxPaidByYear);
    const taxRequiredByYear = @json($taxRequiredByYear);
    const years = Object.keys(incomeByYear);
    const ctx = document.getElementById('incomeByYearChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: years,
            datasets: [
                {
                    label: 'Tổng thu nhập Gross',
                    data: years.map(y => incomeByYear[y] || 0),
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointRadius: 5,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Tổng thuế đã tạm nộp',
                    data: years.map(y => taxPaidByYear[y] || 0),
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                    pointRadius: 5,
                    borderWidth: 3,
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Tổng thuế phải nộp',
                    data: years.map(y => taxRequiredByYear[y] || 0),
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointRadius: 5,
                    borderWidth: 3,
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + Number(context.parsed.y).toLocaleString('vi-VN') + ' VNĐ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' VNĐ';
                        }
                    }
                }
            }
        }
    });
</script>
</x-app-layout>