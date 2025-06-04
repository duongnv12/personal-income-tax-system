<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Người Phụ Thuộc') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Danh sách Người Phụ Thuộc của bạn</h3>
                        <a href="{{ route('dependents.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Thêm Người Phụ Thuộc Mới
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($dependents->isEmpty())
                        <p class="text-gray-600">Bạn chưa có người phụ thuộc nào được khai báo.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 shadow-lg overflow-hidden bg-white">
                                <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Họ và Tên</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Ngày sinh</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Quan hệ</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">CCCD/CMND</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Người khuyết tật</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($dependents as $dependent)
                                        <tr class="hover:bg-blue-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-gray-900">{{ $dependent->full_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ $dependent->dob?->format('d/m/Y') ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ $dependent->relationship }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ $dependent->identification_number ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $dependent->is_disabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ $dependent->is_disabled ? 'Có' : 'Không' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="{{ route('dependents.edit', $dependent) }}" class="inline-block bg-indigo-100 text-indigo-700 hover:bg-indigo-200 hover:text-indigo-900 px-3 py-1 rounded transition mr-2">Sửa</a>
                                                <form action="{{ route('dependents.destroy', $dependent) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa người phụ thuộc này?');">
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