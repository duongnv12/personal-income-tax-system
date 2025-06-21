<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-building-columns mr-2 text-blue-600"></i> {{ __('Quản lý Nguồn Thu Nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300"> {{-- Thêm border và hiệu ứng hover --}}
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-4">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                            <i class="fa-solid fa-list-ul mr-2 text-purple-600"></i> Danh sách Nguồn Thu Nhập
                        </h3>
                        <a href="{{ route('income-sources.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105"> {{-- Đồng bộ style nút --}}
                            <i class="fa-solid fa-plus-circle mr-2 text-white"></i> {{ __('Thêm Nguồn Thu Nhập Mới') }}
                        </a>
                    </div>

                    <form method="GET" action="{{ route('income-sources.index') }}" class="mb-6 flex items-center gap-2">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Tìm kiếm nguồn thu nhập..."
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

                    @if ($incomeSources->isEmpty())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded-md flex items-center" role="alert"> {{-- Thêm flex và items-center --}}
                            <i class="fa-solid fa-exclamation-triangle mr-3 text-2xl"></i> {{-- Icon lớn hơn một chút --}}
                            <div>
                                <p class="font-bold">Không có dữ liệu!</p>
                                <p>Bạn chưa có nguồn thu nhập nào được khai báo. Hãy thêm một nguồn mới để bắt đầu.</p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên nguồn</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Loại thu nhập</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mã số thuế</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Địa chỉ</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($incomeSources as $source)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $source->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                {{-- Điều chỉnh căn chỉnh icon và văn bản cho thẻ span --}}
                                                @switch($source->income_type)
                                                    @case('salary') <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><i class="fa-solid fa-money-bill-wave mr-1"></i> Tiền lương, tiền công</span> @break
                                                    @case('business') <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><i class="fa-solid fa-briefcase mr-1"></i> Kinh doanh</span> @break
                                                    @case('investment') <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fa-solid fa-chart-line mr-1"></i> Đầu tư</span> @break
                                                    @case('other') <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"><i class="fa-solid fa-ellipsis-h mr-1"></i> Khác</span> @break
                                                    @default N/A
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $source->tax_code ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $source->address ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('income-sources.edit', $source) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 inline-flex items-center">
                                                    <i class="fa-solid fa-edit mr-1"></i> Sửa
                                                </a>
                                                <form action="{{ route('income-sources.destroy', $source) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nguồn thu nhập này? Điều này cũng sẽ xóa tất cả các khoản thu nhập liên quan! Hành động này không thể hoàn tác.');">
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
