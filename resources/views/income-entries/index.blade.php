<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-history mr-2 text-indigo-600"></i> {{ __('Lịch sử Khoản Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-4">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                            <i class="fa-solid fa-receipt mr-2 text-indigo-600"></i> Danh sách các Khoản Thu Nhập
                        </h3>
                        <a href="{{ route('income-entries.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105">
                            <i class="fa-solid fa-plus-circle mr-2 text-white"></i> {{ __('Thêm Khoản Thu Nhập Mới') }}
                        </a>
                    </div>

                    <form method="GET" action="{{ route('income-entries.index') }}" class="mb-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm khoản thu nhập..." class="border rounded px-2 py-1">
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Tìm kiếm</button>
                    </form>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold"><i class="fa-solid fa-check-circle mr-2"></i> Thành công!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold"><i class="fa-solid fa-info-circle mr-2"></i> Thông báo!</p>
                            <p>{{ session('info') }}</p>
                        </div>
                    @endif

                    @if ($incomeEntries->isEmpty())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded-md flex items-center" role="alert">
                            <i class="fa-solid fa-exclamation-triangle mr-3 text-2xl"></i>
                            <div>
                                <p class="font-bold">Không có dữ liệu!</p>
                                <p>Bạn chưa có khoản thu nhập nào được khai báo. Hãy thêm một khoản mới để bắt đầu.</p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Năm</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kỳ tính</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nguồn thu</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Loại thu nhập</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Gross Income</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">BHXH ước tính</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thuế TNCN tạm nộp</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Net Income</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($incomeEntries as $entry)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $entry->year }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                @if ($entry->entry_type === 'monthly')
                                                    <span class="inline-flex items-center text-xs leading-5 font-semibold">Tháng {{ $entry->month }}</span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800"><i class="fa-solid fa-calendar-alt mr-1"></i> Cả năm</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $entry->incomeSource->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($entry->incomeSource->income_type)
                                                    @case('salary') <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><i class="fa-solid fa-money-bill-wave mr-1"></i> Tiền lương, tiền công</span> @break
                                                    @case('business') <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><i class="fa-solid fa-briefcase mr-1"></i> Kinh doanh</span> @break
                                                    @case('investment') <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fa-solid fa-chart-line mr-1"></i> Đầu tư</span> @break
                                                    @case('other') <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"><i class="fa-solid fa-ellipsis-h mr-1"></i> Khác</span> @break
                                                    @default N/A
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-green-700">{{ number_format($entry->gross_income, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-gray-600">{{ number_format($entry->bhxh_deduction ?? 0, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-red-600">{{ number_format($entry->tax_paid ?? 0, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-blue-700">{{ number_format($entry->net_income ?? 0, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- <button onclick="showDetails({{ $entry->id }})" class="text-blue-600 hover:text-blue-900" title="Xem chi tiết">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button> -->
                                                    <a href="{{ route('income-entries.edit', $entry->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Sửa">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <form action="{{ route('income-entries.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khoản thu nhập này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $incomeEntries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <x-modal name="calculation-result" :show="false" maxWidth="2xl" focusable>
        <div class="p-6" id="details-modal-content">
            
        </div>
    </x-modal>

    <script>
        function showDetails(entryId) {
            fetch(`/income-entries/show/${entryId}`)
                .then(response => response.json())
                .then(data => {
                    const result = data.result;
                    if (result) {
                        const content = `
                            <div class="flex justify-between items-start">
                                <h2 class="text-2xl font-bold text-gray-900">
                                    <i class="fa-solid fa-calculator mr-2 text-green-600"></i> Kết quả tính lương chi tiết
                                </h2>
                                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                             <div class="mt-6">
                                <h3 class="font-semibold text-lg mb-2 text-gray-800">Bảng tóm tắt</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center p-4 bg-gray-50 rounded-lg border">
                                    <div>
                                        <p class="text-sm text-gray-600">Lương Gross</p>
                                        <p class="font-bold text-lg text-gray-800">${(result.actual_gross_income || 0).toLocaleString('vi-VN')}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Bảo hiểm</p>
                                        <p class="font-bold text-lg text-red-600">-${(result.actual_bhxh_deduction || 0).toLocaleString('vi-VN')}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Thuế TNCN</p>
                                        <p class="font-bold text-lg text-red-600">-${(result.actual_tax_paid || 0).toLocaleString('vi-VN')}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Lương Net</p>
                                        <p class="font-bold text-lg text-green-700">${(result.actual_net_income || 0).toLocaleString('vi-VN')}</p>
                                    </div>
                                </div>

                                <h4 class="font-semibold text-gray-800 mt-6 mb-2">Diễn giải chi tiết (VND)</h4>
                                <div class="border rounded-lg overflow-hidden">
                                    <table class="min-w-full">
                                        <tbody class="divide-y divide-gray-200">
                                            <tr class="bg-white"><td class="px-4 py-2">Lương GROSS</td><td class="px-4 py-2 text-right font-semibold">${(result.actual_gross_income || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-gray-50"><td class="px-4 py-2 pl-6">Bảo hiểm xã hội (8%)</td><td class="px-4 py-2 text-right">-${(result.bhxh || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-white"><td class="px-4 py-2 pl-6">Bảo hiểm y tế (1.5%)</td><td class="px-4 py-2 text-right">-${(result.bhyt || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-gray-50"><td class="px-4 py-2 pl-6">Bảo hiểm thất nghiệp (1%)</td><td class="px-4 py-2 text-right">-${(result.bhtn || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-white"><td class="px-4 py-2">Thu nhập trước thuế</td><td class="px-4 py-2 text-right font-semibold">${(result.thu_nhap_truoc_thue || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-gray-50"><td class="px-4 py-2">Giảm trừ bản thân</td><td class="px-4 py-2 text-right">-${(result.giam_tru_ban_than || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-white"><td class="px-4 py-2">Giảm trừ người phụ thuộc</td><td class="px-4 py-2 text-right">-${(result.giam_tru_phu_thuoc || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-gray-50"><td class="px-4 py-2 font-bold">Thu nhập tính thuế</td><td class="px-4 py-2 text-right font-bold">${(result.thu_nhap_chiu_thue || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-white"><td class="px-4 py-2">Thuế thu nhập cá nhân (*)</td><td class="px-4 py-2 text-right font-semibold text-red-600">-${(result.actual_tax_paid || 0).toLocaleString('vi-VN')}</td></tr>
                                            <tr class="bg-green-100"><td class="px-4 py-2 font-extrabold text-green-800">Lương NET (Thực nhận)</td><td class="px-4 py-2 text-right font-extrabold text-lg text-green-800">${(result.actual_net_income || 0).toLocaleString('vi-VN')}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                        document.getElementById('details-modal-content').innerHTML = content;
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'calculation-result' }));
                    }
                });
        }

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
    </script>
</x-app-layout>