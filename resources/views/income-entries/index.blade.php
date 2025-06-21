<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-history mr-2 text-indigo-600"></i> {{ __('Lịch sử Khoản Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300">
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

                    <form method="GET" action="{{ route('income-entries.index') }}" class="mb-6 flex items-center gap-2">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Tìm kiếm khoản thu nhập..."
                                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out outline-none hover:border-indigo-400"
                                autocomplete="off">
                        </div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105">
                            <i class="fa-solid fa-search mr-2"></i> Tìm kiếm
                        </button>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($incomeEntries as $entry)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-5 flex flex-col justify-between h-full transition-transform duration-200 hover:scale-[1.02] hover:shadow-lg">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-gray-500">
                                                <i class="fa-solid fa-calendar-alt mr-1"></i>
                                                {{ $entry->year }}
                                                @if ($entry->entry_type === 'monthly')
                                                    - Tháng {{ $entry->month }}
                                                @else
                                                    - <span class="text-purple-700 font-semibold">Cả năm</span>
                                                @endif
                                            </span>
                                            <span class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">
                                                {{ $entry->incomeSource->name }}
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            @switch($entry->incomeSource->income_type)
                                                @case('salary') <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs"><i class="fa-solid fa-money-bill-wave mr-1"></i> Lương</span> @break
                                                @case('business') <span class="inline-block px-2 py-1 rounded bg-green-100 text-green-800 text-xs"><i class="fa-solid fa-briefcase mr-1"></i> Kinh doanh</span> @break
                                                @case('investment') <span class="inline-block px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs"><i class="fa-solid fa-chart-line mr-1"></i> Đầu tư</span> @break
                                                @case('other') <span class="inline-block px-2 py-1 rounded bg-gray-100 text-gray-800 text-xs"><i class="fa-solid fa-ellipsis-h mr-1"></i> Khác</span> @break
                                                @default N/A
                                            @endswitch
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-gray-600 text-sm">Lương Gross:</span>
                                            <span class="font-bold text-green-700">{{ number_format($entry->gross_income, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-gray-600 text-sm">BHXH:</span>
                                            <span class="text-gray-800">{{ number_format($entry->bhxh_deduction ?? 0, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-gray-600 text-sm">Thuế TNCN:</span>
                                            <span class="text-red-600">{{ number_format($entry->tax_paid ?? 0, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 text-sm">Lương Net:</span>
                                            <span class="font-bold text-blue-700">{{ number_format($entry->net_income ?? 0, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end mt-4 space-x-2">
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
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $incomeEntries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>