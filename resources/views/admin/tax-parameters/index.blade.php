<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-calculator mr-2 text-purple-600"></i> {{ __('Quản lý Tham số Thuế') }}
        </h2>
    </x-slot>

    <div class="py-12"> {{-- Bỏ bg-gray-100 ở đây vì nó đã có trong app-layout --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 transform hover:scale-[1.005] transition-all duration-300">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-4">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                            <i class="fa-solid fa-coins mr-2 text-yellow-600"></i> Danh sách Tham số Thuế
                        </h3>
                        {{-- Uncomment hoặc thêm nút thêm mới nếu có chức năng này --}}
                        {{-- <a href="{{ route('admin.tax-parameters.create') }}"
                           class="inline-flex items-center px-5 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <i class="fa-solid fa-plus-circle mr-2"></i> {{ __('Thêm Tham số Thuế mới') }}
                        </a> --}}
                    </div>

                    {{-- Thông báo session: Thành công --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Thành công!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Thông báo session: Lỗi --}}
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Lỗi!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($taxParameters->isEmpty())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded-md" role="alert">
                            <p class="font-bold">Không có dữ liệu!</p>
                            <p>Chưa có tham số thuế nào được cấu hình.</p>
                            {{-- Thêm nút tạo mới nếu cần --}}
                            {{-- <a href="{{ route('admin.tax-parameters.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                <i class="fa-solid fa-plus mr-2"></i> Thêm tham số mới
                            </a> --}}
                        </div>
                    @else
                        <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100"> {{-- Thay đổi từ bg-gray-50 thành bg-gray-100 --}}
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Khóa</th> {{-- Thay đổi từ text-gray-700 thành text-gray-600 --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Giá trị</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mô tả</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($taxParameters as $param)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out"> {{-- Thay đổi từ hover:bg-blue-50 thành hover:bg-gray-50, duration-200 thành duration-150 --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $param->param_key }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($param->param_value, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $param->description }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center btn-edit-param" data-id="{{ $param->id }}">
                                                    <i class="fa-solid fa-edit mr-1"></i> Sửa
                                                </button>
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

    <!-- Modal popup chỉnh sửa tham số thuế -->
    <div id="editParamModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            </div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full z-50">
                <div class="p-6" id="editParamModalContent">
                    <!-- Nội dung form sẽ được load ở đây -->
                    <div class="text-center text-gray-500">Đang tải...</div>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-success" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50 hidden">
        Cập nhật thành công!
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý click nút Sửa
            document.querySelectorAll('.btn-edit-param').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.getAttribute('data-id');
                    const modal = document.getElementById('editParamModal');
                    const modalContent = document.getElementById('editParamModalContent');
                    modal.classList.remove('hidden');
                    modalContent.innerHTML = '<div class="text-center text-gray-500">Đang tải...</div>';
                    // Gọi AJAX lấy form
                    fetch(`/admin/tax-parameters/${id}/edit?popup=1`)
                        .then(res => res.text())
                        .then(html => {
                            modalContent.innerHTML = html;
                            // Gắn sự kiện đóng modal
                            const closeBtn = modalContent.querySelector('.btn-close-modal');
                            if (closeBtn) closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
                            // Gắn submit ajax
                            const form = modalContent.querySelector('form');
                            if (form) {
                                form.addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    const formData = new FormData(form);
                                    fetch(form.action, {
                                        method: 'POST',
                                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                        body: formData
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Hiển thị toast
                                            const toast = document.getElementById('toast-success');
                                            toast.classList.remove('hidden');
                                            setTimeout(() => {
                                                toast.classList.add('hidden');
                                                window.location.reload();
                                            }, 1500);
                                        } else {
                                            // Hiển thị lỗi
                                            if (data.html) modalContent.innerHTML = data.html;
                                        }
                                    });
                                });
                            }
                        });
                });
            });
            // Đóng modal khi click ra ngoài
            document.getElementById('editParamModal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>
