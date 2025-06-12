<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{-- text-xl để đồng bộ với các header khác --}}
            <i class="fa-solid fa-users mr-2 text-purple-600"></i> {{ __('Quản lý Người Phụ Thuộc') }} {{-- Thêm icon và màu sắc --}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300"> {{-- Thêm border và hiệu ứng hover --}}
                <div class="p-6 text-gray-900"> {{-- Bỏ sm:p-8 để đồng bộ --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-4"> {{-- Thêm border-b và pb-4 --}}
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0"> {{-- font-bold, text-2xl --}}
                            <i class="fa-solid fa-users-line mr-2 text-teal-600"></i> Danh sách Người Phụ Thuộc {{-- Thêm icon và màu sắc --}}
                        </h3>
                        <a href="{{ route('dependents.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105"> {{-- Đồng bộ style nút --}}
                            <i class="fa-solid fa-user-plus mr-2 text-white"></i> {{ __('Thêm Người Phụ Thuộc Mới') }} {{-- Thay icon SVG bằng Font Awesome --}}
                        </a>
                    </div>

                    {{-- Thông báo thành công --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert"> {{-- Đồng bộ style thông báo --}}
                            <p class="font-bold"><i class="fa-solid fa-check-circle mr-2"></i> Thành công!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    {{-- Thông báo lỗi (nếu có) --}}
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold"><i class="fa-solid fa-times-circle mr-2"></i> Lỗi!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($dependents->isEmpty())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded-md flex items-center" role="alert"> {{-- Đồng bộ style thông báo rỗng --}}
                            <i class="fa-solid fa-exclamation-triangle mr-3 text-2xl"></i> {{-- Icon lớn hơn một chút --}}
                            <div>
                                <p class="font-bold">Không có dữ liệu!</p>
                                <p>Bạn chưa có người phụ thuộc nào được đăng ký. Hãy thêm một người mới để bắt đầu.</p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200"> {{-- Đồng bộ style bảng --}}
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100"> {{-- bg-gray-100 --}}
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Họ và tên</th> {{-- text-gray-600, font-semibold --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ngày sinh</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">CCCD/MST NPT</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mối quan hệ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ngày ĐK Giảm trừ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ngày KT Giảm trừ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th> {{-- Căn phải hành động --}}
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($dependents as $dependent)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out"> {{-- hover:bg-gray-50, duration-150 --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dependent->full_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->dob->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->identification_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{-- Điều chỉnh căn chỉnh icon và văn bản cho mối quan hệ nếu có icon --}}
                                                {{ $dependent->relationship }} {{-- Giả định không có icon riêng cho mối quan hệ, chỉ là text --}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->registration_date ? $dependent->registration_date->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dependent->deactivation_date ? $dependent->deactivation_date->format('d/m/Y') : 'Chưa kết thúc' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- Căn chỉnh icon và văn bản cho Trạng thái --}}
                                                @if($dependent->status == 'active')
                                                    <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><i class="fa-solid fa-circle-check mr-1"></i> Hoạt động</span> {{-- Thêm icon --}}
                                                @else
                                                    <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"><i class="fa-solid fa-circle-xmark mr-1"></i> Không hoạt động</span> {{-- Thêm icon --}}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('dependents.edit', $dependent) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 inline-flex items-center"> {{-- Thêm inline-flex items-center --}}
                                                    <i class="fa-solid fa-edit mr-1"></i> Sửa
                                                </a>
                                                <form action="{{ route('dependents.destroy', $dependent) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người phụ thuộc này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center"> {{-- Thêm inline-flex items-center --}}
                                                        <i class="fa-solid fa-trash-alt mr-1"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Thêm phân trang nếu có --}}
                        {{-- <div class="mt-6"> --}}{{-- Thay mt-4 bằng mt-6 --}}
                            {{-- {{ $dependents->links() }} --}}
                        {{-- </div> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
