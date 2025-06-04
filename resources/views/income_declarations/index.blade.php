<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Khai Báo Thu Nhập Hàng Tháng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Danh sách Khai báo của bạn</h3>
                        <a href="{{ route('income_declarations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Thêm Khai báo Mới
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($declarations->isEmpty())
                        <p class="text-gray-600">Bạn chưa có khai báo thu nhập nào. Hãy thêm một khai báo để bắt đầu!</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 shadow-lg overflow-hidden bg-white">
                                <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Tháng/Năm</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Lương Gross</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Thu nhập khác chịu thuế</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Thuế TNCN đã tính</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Lương Net</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($declarations as $declaration)
                                        <tr class="hover:bg-blue-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-gray-900">{{ $declaration->declaration_month->format('m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ number_format($declaration->gross_salary, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ number_format($declaration->other_taxable_income, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ number_format($declaration->calculated_tax, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ number_format($declaration->net_salary, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="{{ route('income_declarations.show', $declaration) }}" class="inline-block bg-blue-100 text-blue-700 hover:bg-blue-200 hover:text-blue-900 px-3 py-1 rounded transition mr-2">Chi tiết</a>
                                                <a href="{{ route('income_declarations.edit', $declaration) }}" class="inline-block bg-indigo-100 text-indigo-700 hover:bg-indigo-200 hover:text-indigo-900 px-3 py-1 rounded transition mr-2">Sửa</a>
                                                <form action="{{ route('income_declarations.destroy', $declaration) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa khai báo này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-block bg-red-100 text-red-700 hover:bg-red-200 hover:text-red-900 px-3 py-1 rounded transition">Xóa</button>
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