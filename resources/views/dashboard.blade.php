<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Tổng Quan Thuế TNCN Của Bạn') }}
        </h2>
    </x-slot>

    <div class="py-8 md:py-12 bg-gray-50"> {{-- Thêm bg-gray-50 cho nền nhẹ nhàng --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl"> {{-- Shadow lớn hơn, rounded đẹp hơn --}}
                <div class="p-6 sm:p-10 text-gray-900">

                    {{-- Section: Tóm tắt Thuế TNCN Năm --}}
                    <h3 class="text-3xl font-bold text-gray-800 mb-8 text-center leading-tight">
                        {{ __('Tóm Tắt Thuế TNCN Năm') }} <span class="text-indigo-600">{{ $currentYear }}</span>
                    </h3>

                    @if ($annualSummary)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                            {{-- Card: Tổng Lương Gross Năm --}}
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-blue-200">
                                <h4 class="text-lg font-semibold text-blue-800 mb-3">{{ __('Tổng Lương Gross Năm') }}</h4>
                                <p class="text-4xl font-extrabold text-blue-700">{{ number_format($annualSummary['annual_gross_salary'], 0, ',', '.') }} <span class="text-2xl font-semibold">VNĐ</span></p>
                            </div>
                            {{-- Card: Tổng Thuế TNCN Phải Nộp Năm --}}
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-green-200">
                                <h4 class="text-lg font-semibold text-green-800 mb-3">{{ __('Tổng Thuế TNCN Phải Nộp Năm') }}</h4>
                                <p class="text-4xl font-extrabold text-green-700">{{ number_format($annualSummary['annual_pit_amount'], 0, ',', '.') }} <span class="text-2xl font-semibold">VNĐ</span></p>
                            </div>
                            {{-- Card: Tổng Lương Net Thực Nhận Năm --}}
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-purple-200">
                                <h4 class="text-lg font-semibold text-purple-800 mb-3">{{ __('Tổng Lương Net Thực Nhận Năm') }}</h4>
                                <p class="text-4xl font-extrabold text-purple-700">{{ number_format($annualSummary['annual_gross_salary'] - $annualSummary['annual_social_insurance_contribution'] - $annualSummary['annual_pit_amount'], 0, ',', '.') }} <span class="text-2xl font-semibold">VNĐ</span></p>
                            </div>
                            {{-- Card: Tổng BH Bắt Buộc Đã Đóng Năm --}}
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-yellow-200">
                                <h4 class="text-lg font-semibold text-yellow-800 mb-3">{{ __('Tổng BH Bắt Buộc Đã Đóng Năm') }}</h4>
                                <p class="text-4xl font-extrabold text-yellow-700">{{ number_format($annualSummary['annual_social_insurance_contribution'], 0, ',', '.') }} <span class="text-2xl font-semibold">VNĐ</span></p>
                            </div>
                            {{-- Card: Số Thuế Cần Nộp Thêm / Hoàn Lại --}}
                            <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-red-200">
                                <h4 class="text-lg font-semibold text-red-800 mb-3">{{ __('Số Thuế Cần Nộp Thêm / Hoàn Lại') }}</h4>
                                @php
                                    $taxStatusClass = $annualSummary['tax_to_pay_or_refund'] > 0 ? 'text-red-700' : ($annualSummary['tax_to_pay_or_refund'] < 0 ? 'text-green-700' : 'text-gray-700');
                                    $taxStatusText = $annualSummary['tax_to_pay_or_refund'] > 0 ? 'Cần nộp thêm' : ($annualSummary['tax_to_pay_or_refund'] < 0 ? 'Được hoàn lại' : 'Không có');
                                @endphp
                                <p class="text-4xl font-extrabold {{ $taxStatusClass }}">
                                    {{ number_format(abs($annualSummary['tax_to_pay_or_refund']), 0, ',', '.') }} <span class="text-2xl font-semibold">VNĐ</span>
                                </p>
                                <p class="text-sm text-gray-600 mt-2">({{ $taxStatusText }})</p>
                            </div>
                            {{-- Card: Số Người Phụ Thuộc Hợp Lệ (Ước tính) --}}
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3">{{ __('Số Người Phụ Thuộc Hợp Lệ') }}</h4>
                                @php
                                    $totalDependentMonths = 0;
                                    if ($dependentDeductionAmountPerMonth > 0) {
                                        $totalDependentMonths = $annualSummary['annual_dependent_deduction'] / $dependentDeductionAmountPerMonth;
                                    }
                                    $dependentCount = $totalDependentMonths > 0 ? round($totalDependentMonths / 12, 1) : 0;
                                @endphp
                                <p class="text-4xl font-extrabold text-gray-700">{{ $dependentCount }} <span class="text-2xl font-semibold">{{ __('người') }}</span></p>
                                <p class="text-sm text-gray-600 mt-2">
                                    <a href="{{ route('dependents.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline transition duration-300">{{ __('Xem chi tiết') }}</a>
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600 text-center text-lg mt-8 mb-8 p-4 bg-yellow-50 rounded-lg shadow-inner border border-yellow-200">
                            {{ __('Chưa có dữ liệu khai báo thu nhập cho năm') }} <span class="font-semibold">{{ $currentYear }}</span>. {{ __('Vui lòng thêm khai báo để xem tổng quan.') }}
                        </p>
                    @endif

                    {{-- Section: Khai Báo Gần Nhất --}}
                    <h3 class="text-3xl font-bold text-gray-800 mb-6 text-center leading-tight">{{ __('Khai Báo Gần Nhất') }}</h3>
                    @if ($latestMonthlyDeclaration)
                        <div class="bg-indigo-50 p-6 md:p-8 rounded-xl shadow-lg border border-indigo-200">
                            <h4 class="text-xl md:text-2xl font-extrabold text-indigo-800 mb-5 flex items-center">
                                <svg class="h-8 w-8 mr-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('Tháng') }} {{ $latestMonthlyDeclaration->declaration_month->format('m/Y') }}
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-lg">
                                <div>
                                    <p class="text-gray-700 mb-2"><strong>{{ __('Lương Gross:') }}</strong> <span class="font-semibold">{{ number_format($latestMonthlyDeclaration->gross_salary, 0, ',', '.') }} VNĐ</span></p>
                                    <p class="text-gray-700 mb-2"><strong>{{ __('Thu nhập khác:') }}</strong> <span class="font-semibold">{{ number_format($latestMonthlyDeclaration->other_taxable_income, 0, ',', '.') }} VNĐ</span></p>
                                    <p class="text-gray-700 mb-2"><strong>{{ __('Thu nhập chịu thuế (Trước giảm trừ):') }}</strong> <span class="font-semibold">{{ number_format($latestMonthlyDeclaration->gross_salary + $latestMonthlyDeclaration->other_taxable_income - $latestMonthlyDeclaration->non_taxable_income, 0, ',', '.') }} VNĐ</span></p>
                                </div>
                                <div>
                                    <p class="text-gray-700 mb-2"><strong>{{ __('BH Bắt buộc:') }}</strong> <span class="font-semibold">{{ number_format($latestMonthlyDeclaration->social_insurance_contribution, 0, ',', '.') }} VNĐ</span></p>
                                    <p class="text-gray-700 mb-2"><strong>{{ __('Tổng giảm trừ:') }}</strong> <span class="font-semibold">{{ number_format($latestMonthlyDeclaration->total_deduction, 0, ',', '.') }} VNĐ</span></p>
                                    <p class="text-gray-700 mb-2"><strong>{{ __('Thu nhập tính thuế:') }}</strong> <span class="font-semibold">{{ number_format($latestMonthlyDeclaration->taxable_income, 0, ',', '.') }} VNĐ</span></p>
                                </div>
                                <div class="col-span-1 md:col-span-2 mt-4 pt-4 border-t border-indigo-200">
                                    <p class="text-gray-900 mb-2 text-xl md:text-2xl"><strong>{{ __('Thuế TNCN:') }}</strong> <span class="font-extrabold text-red-600">{{ number_format($latestMonthlyDeclaration->calculated_tax, 0, ',', '.') }} VNĐ</span></p>
                                    <p class="text-gray-900 mb-4 text-xl md:text-2xl"><strong>{{ __('Lương Net:') }}</strong> <span class="font-extrabold text-green-600">{{ number_format($latestMonthlyDeclaration->net_salary, 0, ',', '.') }} VNĐ</span></p>
                                    <a href="{{ route('income_declarations.show', $latestMonthlyDeclaration) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300">
                                        {{ __('Xem chi tiết khai báo này') }} &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600 text-center text-lg mt-8 mb-8 p-4 bg-yellow-50 rounded-lg shadow-inner border border-yellow-200">
                            {{ __('Bạn chưa có khai báo thu nhập hàng tháng nào. Hãy thêm khai báo đầu tiên!') }}
                        </p>
                    @endif

                    {{-- Section: Hành Động Nhanh --}}
                    <h3 class="text-3xl font-bold text-gray-800 mb-6 text-center leading-tight mt-10">{{ __('Hành Động Nhanh') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('income_declarations.create') }}" class="block p-5 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transition duration-300 transform hover:-translate-y-1 text-center font-semibold text-lg flex items-center justify-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            {{ __('Thêm Khai Báo Mới') }}
                        </a>
                        <a href="{{ route('income_declarations.index') }}" class="block p-5 bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-indigo-600 hover:to-indigo-700 transition duration-300 transform hover:-translate-y-1 text-center font-semibold text-lg flex items-center justify-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m7 0V5a2 2 0 012-2h2a2 2 0 012 2v6m-6 0H6"></path></svg>
                            {{ __('Quản lý Khai Báo') }}
                        </a>
                        <a href="{{ route('dependents.index') }}" class="block p-5 bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:to-teal-700 transition duration-300 transform hover:-translate-y-1 text-center font-semibold text-lg flex items-center justify-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V4a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h2m-1-9h11m-1-2h.01M10 11H5m10 0a2 2 0 11-4 0 2 2 0 014 0zM7 20h2a2 2 0 002-2v-3a2 2 0 00-2-2H7a2 2 0 00-2 2v3a2 2 0 002 2z"></path></svg>
                            {{ __('Người Phụ Thuộc') }}
                        </a>
                        <a href="{{ route('tax.annual_settlement') }}" class="block p-5 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transition duration-300 transform hover:-translate-y-1 text-center font-semibold text-lg flex items-center justify-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m2 2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-2m-7 4h.01M17 17h.01"></path></svg>
                            {{ __('Quyết Toán Thuế') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>