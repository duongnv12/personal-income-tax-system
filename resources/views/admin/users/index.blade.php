<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-users-cog mr-2 text-indigo-600"></i> {{ __('Quản lý Người Dùng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-4">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                            <i class="fa-solid fa-user-friends mr-2 text-green-600"></i> Danh sách Người Dùng
                        </h3>
                        <a href="{{ route('admin.users.create') }}"
                           class="inline-flex items-center px-5 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <i class="fa-solid fa-user-plus mr-2"></i> {{ __('Thêm Người dùng mới') }}
                        </a>
                    </div>

                    {{-- Thông báo session: Thành công --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Thành công!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Thông báo session: Lỗi (thêm mới) --}}
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Lỗi!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($users->isEmpty())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded-md" role="alert">
                            <p class="font-bold">Không có dữ liệu!</p>
                            <p>Hiện không có người dùng nào khác để quản lý. Hãy thêm người dùng mới.</p>
                        </div>
                    @else
                        {{-- Bảng danh sách người dùng với style tương tự bảng nguồn thu nhập --}}
                        <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quyền</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Trạng thái Email</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($users as $user)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                @if($user->is_admin)
                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="fa-solid fa-user-tie mr-1"></i> Quản trị viên
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                        <i class="fa-solid fa-user mr-1"></i> Người dùng
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                @if($user->hasVerifiedEmail())
                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="fa-solid fa-check-circle mr-1"></i> Đã xác minh
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                        <i class="fa-solid fa-exclamation-triangle mr-1"></i> Chưa xác minh
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                {{-- Kiểm tra để không hiển thị nút sửa/xóa cho tài khoản đang đăng nhập --}}
                                                @if ($user->id !== Auth::id())
                                                    {{-- Nút Sửa --}}
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 inline-flex items-center">
                                                        <i class="fa-solid fa-edit mr-1"></i> Sửa
                                                    </a>
                                                    {{-- Nút Xóa với xác nhận --}}
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                                            <i class="fa-solid fa-trash-alt mr-1"></i> Xóa
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Có thể hiển thị một thông báo hoặc để trống --}}
                                                    <span class="text-gray-500 italic">Tài khoản của bạn</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Phần phân trang --}}
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
