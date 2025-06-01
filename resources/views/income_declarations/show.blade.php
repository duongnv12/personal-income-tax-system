<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi Tiết Khai Báo Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6 text-center">
                        Chi Tiết Khai Báo Thu Nhập Tháng {{ $incomeDeclaration->declaration_month->format('m/Y') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div>
                            <p><strong>Lương Gross:</strong> {{ number_format($incomeDeclaration->gross_salary, 0, ',', '.') }} VNĐ</p>
                            <p><strong>Thu nhập khác chịu thuế:</strong> {{ number_format($incomeDeclaration->other_taxable_income, 0, ',', '.') }} VNĐ</p>
                            <p><strong>Tổng thu nhập chịu thuế:</strong> {{ number_format($detailedCalculation['total_taxable_income'], 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div>
                            <p><strong>Thuế TNCN đã tính:</strong> <span class="font-bold text-red-600">{{ number_format($incomeDeclaration->calculated_tax, 0, ',', '.') }} VNĐ</span></p>
                            <p><strong>Lương Net thực nhận:</strong> <span class="font-bold text-green-600">{{ number_format($incomeDeclaration->net_salary, 0, ',', '.') }} VNĐ</span></p>
                        </div>
                    </div>

                    <h4 class="text-xl font-bold mb-4">Các Bước Tính Thuế Chi Tiết:</h4>
                    <div class="space-y-6">
                        @foreach ($detailedCalculation['steps'] as $step)
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <p class="font-semibold text-lg text-indigo-700">Bước {{ $step['step'] }}: {{ $step['description'] }}</p>
                                <ul class="list-disc list-inside ml-4 mt-2 text-gray-700">
                                    @foreach ($step['details'] as $detail)
                                        @if (is_array($detail))
                                            {{-- Dành cho chi tiết bậc thuế --}}
                                            <li>
                                                <span class="font-medium">Bậc {{ $detail['bracket'] }}:</span> Thu nhập tính trong bậc: {{ $detail['income_in_bracket'] }} VNĐ, Tỷ lệ: {{ $detail['rate'] }} => Thuế: {{ $detail['tax_amount'] }} VNĐ
                                            </li>
                                        @else
                                            <li>{{ $detail }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                                @if ($step['value'] !== null)
                                    <p class="font-bold text-gray-800 mt-2">Kết quả bước này: {{ number_format($step['value'], 0, ',', '.') }} VNĐ</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('income_declarations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Quay lại
                        </a>
                        <a href="{{ route('income_declarations.export_pdf', $incomeDeclaration) }}" class="ml-3 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Xuất PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>