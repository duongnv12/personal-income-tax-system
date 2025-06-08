<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bảng điều khiển cá nhân') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-6 text-lg">Xin chào, <span class="font-semibold text-blue-700">{{ Auth::user()->name }}</span>!</p>

                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-200 pb-3">Quyết toán thuế TNCN năm {{ $yearlyTaxSettlement['year'] }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                        {{-- Card: Tổng thu nhập Gross --}}
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-blue-700">Tổng thu nhập Gross (tất cả):</p>
                                <p class="font-extrabold text-2xl text-blue-900 mt-1">{{ number_format($yearlyTaxSettlement['total_gross_income'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-sack-dollar text-blue-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Tổng giảm trừ BHXH & khác --}}
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-indigo-700">Tổng giảm trừ BHXH & khác:</p>
                                <p class="font-extrabold text-2xl text-indigo-900 mt-1">{{ number_format($yearlyTaxSettlement['total_bhxh_deduction'] + $yearlyTaxSettlement['total_other_deductions'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-hospital-user text-indigo-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Tổng giảm trừ gia cảnh --}}
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-purple-700">Tổng giảm trừ gia cảnh:</p>
                                <p class="font-extrabold text-2xl text-purple-900 mt-1">{{ number_format($yearlyTaxSettlement['total_personal_deductions'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-people-group text-purple-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Thu nhập tính thuế cả năm (Lương) --}}
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-yellow-700">Thu nhập tính thuế (Lương):</p>
                                <p class="font-extrabold text-2xl text-yellow-900 mt-1">{{ number_format($yearlyTaxSettlement['total_taxable_income_yearly'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-calculator text-yellow-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Thuế phải nộp từ Lương --}}
                        <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-red-700">Thuế phải nộp từ Lương:</p>
                                <p class="font-extrabold text-2xl text-red-900 mt-1">{{ number_format($yearlyTaxSettlement['breakdown']['salary']['tax_required'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-file-invoice-dollar text-red-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Tổng Gross từ Kinh doanh --}}
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-green-700">Tổng Gross từ Kinh doanh:</p>
                                <p class="font-extrabold text-2xl text-green-900 mt-1">{{ number_format($yearlyTaxSettlement['breakdown']['business']['gross_income'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-store text-green-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Thuế phải nộp từ Kinh doanh --}}
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-teal-700">Thuế phải nộp từ Kinh doanh:</p>
                                <p class="font-extrabold text-2xl text-teal-900 mt-1">{{ number_format($yearlyTaxSettlement['breakdown']['business']['tax_required'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-receipt text-teal-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Tổng Gross từ Đầu tư --}}
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-orange-700">Tổng Gross từ Đầu tư:</p>
                                <p class="font-extrabold text-2xl text-orange-900 mt-1">{{ number_format($yearlyTaxSettlement['breakdown']['investment']['gross_income'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-chart-line text-orange-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Thuế phải nộp từ Đầu tư --}}
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-amber-700">Thuế phải nộp từ Đầu tư:</p>
                                <p class="font-extrabold text-2xl text-amber-900 mt-1">{{ number_format($yearlyTaxSettlement['breakdown']['investment']['tax_required'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-money-bill-trend-up text-amber-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Tổng thuế đã tạm nộp (tất cả) --}}
                        <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-cyan-700">Tổng thuế đã tạm nộp (tất cả):</p>
                                <p class="font-extrabold text-2xl text-cyan-900 mt-1">{{ number_format($yearlyTaxSettlement['total_tax_paid_provisional'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-hand-holding-dollar text-cyan-400 text-3xl opacity-50"></i>
                        </div>

                        {{-- Card: Tổng thuế phải nộp cả năm (tất cả) --}}
                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-[1.02]">
                            <div>
                                <p class="text-sm font-medium text-pink-700">Tổng thuế phải nộp cả năm (tất cả):</p>
                                <p class="font-extrabold text-2xl text-pink-900 mt-1">{{ number_format($yearlyTaxSettlement['total_tax_required_yearly'], 0, ',', '.') }} VNĐ</p>
                            </div>
                            <i class="fa-solid fa-money-check-dollar text-pink-400 text-3xl opacity-50"></i>
                        </div>
                    </div>

                    {{-- Phần thông báo số thuế còn phải nộp/được hoàn lại --}}
                    <div class="mb-8 p-6 rounded-xl font-bold text-center shadow-lg transform hover:scale-105 transition-transform duration-300
                        @if ($yearlyTaxSettlement['tax_to_pay_or_refund'] > 0)
                            bg-gradient-to-r from-orange-200 to-orange-100 text-orange-900
                        @elseif ($yearlyTaxSettlement['tax_to_pay_or_refund'] < 0)
                            bg-gradient-to-r from-teal-200 to-teal-100 text-teal-900
                        @else
                            bg-gradient-to-r from-gray-200 to-gray-100 text-gray-800
                        @endif
                    ">
                        @if ($yearlyTaxSettlement['tax_to_pay_or_refund'] > 0)
                            <p class="text-2xl mb-2 flex items-center justify-center">
                                <i class="fa-solid fa-arrow-circle-up mr-3 text-red-600"></i> Số thuế còn phải nộp: <span class="ml-2">{{ number_format($yearlyTaxSettlement['tax_to_pay_or_refund'], 0, ',', '.') }} VNĐ</span>
                            </p>
                        @elseif ($yearlyTaxSettlement['tax_to_pay_or_refund'] < 0)
                            <p class="text-2xl mb-2 flex items-center justify-center">
                                <i class="fa-solid fa-arrow-circle-down mr-3 text-green-600"></i> Số thuế được hoàn lại: <span class="ml-2">{{ number_format(abs($yearlyTaxSettlement['tax_to_pay_or_refund']), 0, ',', '.') }} VNĐ</span>
                            </p>
                        @else
                            <p class="text-2xl mb-2 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check mr-3 text-gray-600"></i> Bạn không có số thuế phải nộp thêm hoặc được hoàn lại trong năm {{ $yearlyTaxSettlement['year'] }}.
                            </p>
                        @endif
                        <p class="mt-4 text-sm text-gray-700">
                            <a href="{{ route('tax.yearly_settlement', $yearlyTaxSettlement['year']) }}" class="text-blue-700 hover:text-blue-900 underline font-semibold">Xem chi tiết báo cáo năm {{ $yearlyTaxSettlement['year'] }} <i class="fa-solid fa-arrow-right ml-1"></i></a>
                        </p>
                    </div>

                   

                    <!-- {{-- Phần thông tin người phụ thuộc --}}
                    <h3 class="text-2xl font-bold text-gray-800 mt-10 mb-6 border-b-2 border-gray-200 pb-3">Thông tin người phụ thuộc</h3>
                    @if ($dependents->isEmpty())
                        <div class="bg-gray-50 p-6 rounded-lg text-center shadow-sm">
                            <p class="text-gray-700 mb-4">Bạn chưa khai báo người phụ thuộc nào.</p>
                            <a href="{{ route('dependents.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition ease-in-out duration-150">
                                <i class="fa-solid fa-user-plus mr-2"></i> Thêm người phụ thuộc mới
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto bg-white rounded-lg shadow-md mb-8">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tên</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ngày sinh</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Mối quan hệ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CCCD/MST NPT</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ngày ĐK Giảm trừ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ngày KT Giảm trừ</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($dependents as $dependent)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $dependent->full_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $dependent->dob->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $dependent->relationship }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $dependent->identification_number ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $dependent->registration_date ? $dependent->registration_date->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $dependent->deactivation_date ? $dependent->deactivation_date->format('d/m/Y') : 'Chưa kết thúc' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('dependents.edit', $dependent) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-4">
                                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Sửa
                                                </a>
                                                <form action="{{ route('dependents.destroy', $dependent) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người phụ thuộc này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900">
                                                        <i class="fa-solid fa-trash-can mr-1"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif -->

                   

                    <!-- {{-- Các nguồn thu nhập --}}
                    <h3 class="text-2xl font-bold text-gray-800 mt-10 mb-6 border-b-2 border-gray-200 pb-3">Các nguồn thu nhập</h3>
                    @if ($user->incomeSources->isEmpty())
                        <div class="bg-gray-50 p-6 rounded-lg text-center shadow-sm">
                            <p class="text-gray-700 mb-4">Bạn chưa khai báo nguồn thu nhập nào.</p>
                            <a href="{{ route('income-sources.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition ease-in-out duration-150">
                                <i class="fa-solid fa-briefcase mr-2"></i> Thêm nguồn thu nhập mới
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto bg-white rounded-lg shadow-md mb-8">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tên nguồn</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Loại thu nhập</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Mã số thuế</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Địa chỉ</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($user->incomeSources as $source)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $source->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                @switch($source->income_type)
                                                    @case('salary') Tiền lương, tiền công @break
                                                    @case('business') Kinh doanh @break
                                                    @case('investment') Đầu tư @break
                                                    @case('other') Khác @break
                                                    @default N/A
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $source->tax_code ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $source->address ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('income-sources.edit', $source) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Sửa
                                                </a>
                                                <form action="{{ route('income-sources.destroy', $source) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nguồn thu nhập này? Điều này cũng sẽ xóa tất cả các khoản thu nhập liên quan!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900">
                                                        <i class="fa-solid fa-trash-can mr-1"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif -->

                   

                    <!-- {{-- Các khoản thu nhập --}}
                    <h3 class="text-2xl font-bold text-gray-800 mt-10 mb-6 border-b-2 border-gray-200 pb-3">Các khoản thu nhập đã nhập</h3>
                    @if ($incomeEntries->isEmpty())
                        <div class="bg-gray-50 p-6 rounded-lg text-center shadow-sm">
                            <p class="text-gray-700 mb-4">Bạn chưa khai báo khoản thu nhập nào.</p>
                            <a href="{{ route('income-entries.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition ease-in-out duration-150">
                                <i class="fa-solid fa-money-bill-transfer mr-2"></i> Thêm khoản thu nhập mới
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Năm</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tháng</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nguồn</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Loại thu nhập</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Loại nhập</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Gross</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">BHXH</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Thuế tạm nộp</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Net ước tính</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($incomeEntries as $entry)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $entry->year }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                @if ($entry->entry_type === 'monthly')
                                                    {{ $entry->month }}
                                                @else
                                                    Cả năm
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $entry->incomeSource->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                @switch($entry->income_type)
                                                    @case('salary') Tiền lương, tiền công @break
                                                    @case('business') Kinh doanh @break
                                                    @case('investment') Đầu tư @break
                                                    @case('other') Khác @break
                                                    @default N/A
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $entry->entry_type === 'monthly' ? 'Hàng tháng' : 'Hàng năm (ổn định)' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-800">{{ number_format($entry->gross_income, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-800">{{ number_format($entry->bhxh_deduction ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-800">{{ number_format($entry->tax_paid ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-800">{{ number_format($entry->net_income ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('income-entries.edit', $entry) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Sửa
                                                </a>
                                                <form action="{{ route('income-entries.destroy', $entry) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khoản thu nhập này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900">
                                                        <i class="fa-solid fa-trash-can mr-1"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>