<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quyết Toán Thuế TNCN Cuối Năm') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6 text-center">Quyết Toán Thuế Thu Nhập Cá Nhân Năm {{ $year }}</h3>

                    <form action="{{ route('tax.annual_settlement') }}" method="GET" class="mb-6 flex items-center justify-center">
                        <label for="year" class="block text-sm font-medium text-gray-700 mr-2">Chọn Năm:</label>
                        <select name="year" id="year" class="form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @for ($y = Carbon\Carbon::now()->year; $y >= 2000; $y--)
                                <option value="{{ $y }}" @if ($y == $year) selected @endif>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="ml-4 px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Xem Quyết Toán
                        </button>
                    </form>

                    @if (isset($settlementResults) && !empty($settlementResults) && !empty($settlementResults['monthly_summaries']))
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-4">Các Số Liệu Tổng Hợp Cả Năm</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                                    <p class="text-lg font-semibold text-blue-800 mb-2">Tổng Thu Nhập Chịu Thuế (Trước giảm trừ)</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ number_format($settlementResults['annual_total_taxable_income'], 0, ',', '.') }} VNĐ</p>
                                </div>
                                <div class="bg-green-50 p-6 rounded-lg shadow-md">
                                    <p class="text-lg font-semibold text-green-800 mb-2">Tổng Giảm Trừ Bản Thân</p>
                                    <p class="text-2xl font-bold text-green-600">{{ number_format($settlementResults['annual_personal_deduction'], 0, ',', '.') }} VNĐ</p>
                                </div>
                                <div class="bg-purple-50 p-6 rounded-lg shadow-md">
                                    <p class="text-lg font-semibold text-purple-800 mb-2">Tổng Giảm Trừ Người Phụ Thuộc</p>
                                    <p class="text-2xl font-bold text-purple-600">{{ number_format($settlementResults['annual_dependent_deduction'], 0, ',', '.') }} VNĐ</p>
                                </div>
                                <div class="bg-yellow-50 p-6 rounded-lg shadow-md">
                                    <p class="text-lg font-semibold text-yellow-800 mb-2">Tổng BH Bắt Buộc Đã Đóng</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($settlementResults['annual_social_insurance_contribution'], 0, ',', '.') }} VNĐ</p>
                                </div>
                                <div class="bg-teal-50 p-6 rounded-lg shadow-md">
                                    <p class="text-lg font-semibold text-teal-800 mb-2">Tổng Các Khoản Giảm Trừ Khác (Từ thiện)</p>
                                    <p class="text-2xl font-bold text-teal-600">{{ number_format($settlementResults['annual_deduction_charity'], 0, ',', '.') }} VNĐ</p>
                                </div>
                                <div class="bg-red-50 p-6 rounded-lg shadow-md">
                                    <p class="text-lg font-semibold text-red-800 mb-2">Tổng Thu Nhập Tính Thuế Cả Năm</p>
                                    <p class="text-2xl font-bold text-red-600">{{ number_format($settlementResults['annual_taxable_income'], 0, ',', '.') }} VNĐ</p>
                                </div>
                                <div class="bg-indigo-50 p-6 rounded-lg shadow-md">
                                    <p class="text-lg font-semibold text-indigo-800 mb-2">Tổng Thuế TNCN Phải Nộp Cả Năm</p>
                                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($settlementResults['annual_pit_amount'], 0, ',', '.') }} VNĐ</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                    <p class="text-lg font-semibold text-gray-800 mb-2">Tổng Thuế Đã Tạm Nộp/Khấu Trừ</p>
                                    <p class="text-2xl font-bold text-gray-600">{{ number_format($settlementResults['annual_tax_deducted_at_source'], 0, ',', '.') }} VNĐ</p>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-md border-2
                                    @if($settlementResults['tax_to_pay_or_refund'] > 0) border-red-500 text-red-700
                                    @elseif($settlementResults['tax_to_pay_or_refund'] < 0) border-green-500 text-green-700
                                    @else border-gray-500 text-gray-700 @endif">
                                    <p class="text-lg font-semibold mb-2">Số Thuế Phải Nộp Thêm / Được Hoàn Lại</p>
                                    <p class="text-3xl font-bold">
                                        {{ number_format(abs($settlementResults['tax_to_pay_or_refund']), 0, ',', '.') }} VNĐ
                                    </p>
                                    <p class="text-sm mt-1">({{ $settlementResults['status'] }})</p>
                                </div>
                            </div>
                            <div class="mt-6 text-right">
                                <a href="{{ route('tax.export_annual_settlement_pdf', ['year' => $year]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <i class="fa-solid fa-file-pdf mr-2"></i> Xuất PDF Quyết Toán
                                </a>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-4">Chi Tiết Các Bước Tính Toán</h4>
                            <div class="space-y-4">
                                @foreach ($settlementResults['steps'] as $step)
                                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                        <p class="font-semibold text-lg text-gray-800">Bước {{ $step['step'] }}: {{ $step['description'] }}</p>
                                        <ul class="list-disc list-inside text-gray-700 mt-2">
                                            @foreach ((array)$step['details'] as $detail) {{-- Cast to array to handle string or array details --}}
                                                <li>{{ $detail }}</li>
                                            @endforeach
                                        </ul>
                                        @if ($step['value'] !== null)
                                            <p class="mt-2 text-md font-bold text-gray-900">Kết quả: {{ number_format($step['value'], 0, ',', '.') }} VNĐ</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-4">Tổng Hợp Khai Báo Thu Nhập Hàng Tháng</h4>
                            @if (!empty($settlementResults['monthly_summaries']))
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tháng</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TN Khác</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TN Miễn Thuế</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BH Bắt Buộc</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giảm trừ PT</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giảm trừ NPT</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Từ thiện</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TN Tính Thuế</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thuế TNCN</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Thực nhận</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($settlementResults['monthly_summaries'] as $summary)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $summary['month'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['gross_salary'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['other_taxable_income'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['non_taxable_income'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['social_insurance_contribution'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['personal_deduction'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['dependent_deduction'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['deduction_charity'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['taxable_income'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['calculated_tax'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($summary['net_salary'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <p class="text-gray-600 text-center">Không có khai báo thu nhập hàng tháng nào cho năm này.</p>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-600 text-center mt-8 mb-8">
                            Chưa có dữ liệu quyết toán thuế cho năm {{ $year }}. Vui lòng đảm bảo bạn đã có các khai báo thu nhập hàng tháng trong năm này.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>