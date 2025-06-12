<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{-- text-xl để đồng bộ với các header khác --}}
            <i class="fa-solid fa-sitemap mr-2 text-blue-600"></i> {{ __('Quản lý Bậc Thuế Lũy tiến') }} {{-- Thêm icon --}}
        </h2>
    </x-slot>

    <div class="py-12"> {{-- Bỏ bg-gray-100 vì đã có trong app-layout --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300"> {{-- shadow-xl, border-gray-100, và hiệu ứng hover --}}
                <div class="p-6 text-gray-900"> {{-- Bỏ sm:p-8 để đồng bộ --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-4"> {{-- Thêm border-b và pb-4 --}}
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0"> {{-- font-bold, text-2xl --}}
                            <i class="fa-solid fa-layer-group mr-2 text-purple-600"></i> Danh sách Bậc thuế
                        </h3>
                        <a href="{{ route('admin.tax-brackets.create') }}" class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md"> {{-- Đồng bộ style nút --}}
                            <i class="fa-solid fa-plus-circle mr-2"></i> {{ __('Thêm Bậc Thuế') }} {{-- Thay icon SVG bằng Font Awesome --}}
                        </a>
                    </div>

                    {{-- Thông báo thành công --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert"> {{-- Đồng bộ style thông báo --}}
                            <p class="font-bold">Thành công!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    {{-- Thông báo lỗi (nếu có) --}}
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert"> {{-- Đồng bộ style thông báo --}}
                            <p class="font-bold">Lỗi!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($taxBrackets->isEmpty())
                        <div class="text-center py-16 bg-gray-50 rounded-lg border border-dashed border-gray-300 transform hover:scale-[1.01] transition-transform duration-300"> {{-- Đồng bộ style thông báo rỗng --}}
                            <p class="text-xl text-gray-600 mb-4">Chưa có bậc thuế lũy tiến nào được cấu hình.</p>
                            <a href="{{ route('admin.tax-brackets.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-500 border border-transparent rounded-lg font-bold text-base text-white uppercase tracking-wider hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <i class="fa-solid fa-plus-circle mr-3"></i> {{ __('Thêm Bậc Thuế mới') }}
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200"> {{-- Đồng bộ style bảng --}}
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100"> {{-- bg-gray-100 --}}
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cấp độ</th> {{-- text-gray-600 --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thu nhập từ (VNĐ)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thu nhập đến (VNĐ)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thuế suất (%)</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($taxBrackets as $bracket)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out"> {{-- hover:bg-gray-50, duration-150 --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bracket->level }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($bracket->income_from, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $bracket->income_to ? number_format($bracket->income_to, 0, ',', '.') . ' VNĐ' : 'Trên' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ($bracket->tax_rate * 100) }}%</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.tax-brackets.edit', $bracket) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 inline-flex items-center"> {{-- text-indigo-600, inline-flex items-center --}}
                                                    <i class="fa-solid fa-edit mr-1"></i> Sửa
                                                </a>
                                                <form action="{{ route('admin.tax-brackets.destroy', $bracket) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bậc thuế này? Thao tác này không thể hoàn tác.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center"> {{-- inline-flex items-center --}}
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
