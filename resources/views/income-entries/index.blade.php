<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-sack-dollar mr-2 text-green-600"></i> {{ __('Quản lý Khoản Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300"> {{-- Thêm border và hiệu ứng hover --}}
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-4">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                            <i class="fa-solid fa-receipt mr-2 text-indigo-600"></i> Danh sách Khoản Thu Nhập
                        </h3>
                        <a href="{{ route('income-entries.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105"> {{-- Đồng bộ style nút --}}
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
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded-md flex items-center" role="alert"> {{-- Thêm flex và items-center --}}
                            <i class="fa-solid fa-exclamation-triangle mr-3 text-2xl"></i> {{-- Icon lớn hơn một chút --}}
                            <div>
                                <p class="font-bold">Không có dữ liệu!</p>
                                <p>Bạn chưa có khoản thu nhập nào được khai báo. Hãy thêm một khoản mới để bắt đầu.</p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-visible shadow-md rounded-lg border border-gray-200">
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
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Net Income ước tính</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($incomeEntries as $entry)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $entry->year }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                {{-- Căn chỉnh icon và văn bản cho Kỳ tính --}}
                                                @if ($entry->entry_type === 'monthly')
                                                    <span class="inline-flex items-center text-xs leading-5 font-semibold">Tháng {{ $entry->month }}</span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800"><i class="fa-solid fa-calendar-alt mr-1"></i> Cả năm</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $entry->incomeSource->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- Điều chỉnh căn chỉnh icon và văn bản cho thẻ span --}}
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
                                                <a href="{{ route('income-entries.edit', $entry) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 inline-flex items-center">
                                                    <i class="fa-solid fa-edit mr-1"></i> Sửa
                                                </a>
                                                <form action="{{ route('income-entries.destroy', $entry) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khoản thu nhập này? Hành động này không thể hoàn tác.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                                        <i class="fa-solid fa-trash-alt mr-1"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
