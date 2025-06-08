<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Quản lý Người Phụ Thuộc') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Danh sách Người Phụ Thuộc</h3>
                        <a href="{{ route('dependents.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            {{ __('Thêm Người Phụ Thuộc') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <strong class="font-bold">Thành công!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </div>
                    @endif

                    @if ($dependents->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-lg text-gray-600 mb-4">Bạn chưa có người phụ thuộc nào được đăng ký.</p>
                            <a href="{{ route('dependents.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Thêm người phụ thuộc đầu tiên') }}
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Họ và tên</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Ngày sinh</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">CCCD/MST NPT</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mối quan hệ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Ngày ĐK Giảm trừ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Ngày KT Giảm trừ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng thái</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($dependents as $dependent)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dependent->full_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->dob->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->identification_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->relationship }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->registration_date ? $dependent->registration_date->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->deactivation_date ? $dependent->deactivation_date->format('d/m/Y') : 'Chưa kết thúc' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($dependent->status == 'active')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hoạt động</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Không hoạt động</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('dependents.edit', $dependent) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 transition duration-150 ease-in-out">Sửa</a>
                                                <form action="{{ route('dependents.destroy', $dependent) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người phụ thuộc này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Thêm phân trang nếu có --}}
                        {{-- <div class="mt-4">
                            {{ $dependents->links() }}
                        </div> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>