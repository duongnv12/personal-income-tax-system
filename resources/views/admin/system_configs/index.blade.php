<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Cấu hình Hệ thống') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Thành công!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Lỗi!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold">Danh sách Cấu hình</h3>
                        <a href="{{ route('admin.system_configs.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Thêm Cấu hình Mới') }}
                        </a>
                    </div>

                    {{-- Form thêm nhanh cấu hình mới --}}
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner mb-8">
                        <h4 class="text-xl font-bold mb-4">Thêm Cấu hình Nhanh</h4>
                        <form action="{{ route('admin.system_configs.store_quick') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <x-input-label for="quick_key" :value="__('Key Cấu hình')" />
                                    <select id="quick_key" name="quick_key" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Chọn hoặc nhập mới</option>
                                        @foreach($existingKeys as $key)
                                            <option value="{{ $key }}" {{ old('quick_key') == $key ? 'selected' : '' }}>
                                                {{ __('admin/config.' . $key) }} ({{ $key }})
                                            </option>
                                        @endforeach
                                        <option value="custom_key">--- Nhập key mới ---</option>
                                    </select>
                                    <x-text-input id="new_quick_key" name="quick_key_new" type="text" class="mt-1 block w-full hidden" placeholder="Nhập key mới..." />
                                    <x-input-error class="mt-2" :messages="$errors->get('quick_key')" />
                                </div>
                                <div>
                                    <x-input-label for="quick_value" :value="__('Giá trị')" />
                                    <x-text-input id="quick_value" name="quick_value" type="number" step="any" class="mt-1 block w-full"
                                                  :value="old('quick_value')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('quick_value')" />
                                </div>
                                <div>
                                    <x-input-label for="quick_effective_date" :value="__('Ngày hiệu lực')" />
                                    <x-text-input id="quick_effective_date" name="quick_effective_date" type="date" class="mt-1 block w-full"
                                                  :value="old('quick_effective_date', \Carbon\Carbon::now()->format('Y-m-d'))" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('quick_effective_date')" />
                                </div>
                                <div>
                                    <x-input-label for="quick_description" :value="__('Mô tả')" />
                                    <x-text-input id="quick_description" name="quick_description" type="text" class="mt-1 block w-full"
                                                  :value="old('quick_description')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('quick_description')" />
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <x-primary-button>
                                    {{ __('Thêm Nhanh') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>


                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Key Cấu hình') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Giá trị') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Ngày hiệu lực') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Mô tả') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Hành động') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($latestConfigs as $config) {{-- Sử dụng $latestConfigs để hiển thị các key duy nhất --}}
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            {{-- Sử dụng helper __() để dịch key --}}
                                            {{ __('admin/config.' . $config->key) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            @if (str_contains($config->key, 'rate'))
                                                {{ ($config->value * 100) . '%' }}
                                            @else
                                                {{ number_format($config->value, 0, ',', '.') }} VNĐ
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ \Carbon\Carbon::parse($config->effective_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ $config->description ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-center text-sm leading-5 font-medium">
                                            <a href="{{ route('admin.system_configs.edit', $config->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                                                {{ __('Sửa') }}
                                            </a>
                                            <form action="{{ route('admin.system_configs.destroy', $config->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa cấu hình này không? Hành động này không thể hoàn tác!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    {{ __('Xóa') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($latestConfigs->isEmpty())
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-sm leading-5 text-gray-500 text-center">
                                            {{ __('Chưa có cấu hình nào được tạo.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script để xử lý việc chọn/nhập key mới --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quickKeySelect = document.getElementById('quick_key');
            const newQuickKeyInput = document.getElementById('new_quick_key');

            quickKeySelect.addEventListener('change', function () {
                if (this.value === 'custom_key') {
                    newQuickKeyInput.classList.remove('hidden');
                    newQuickKeyInput.setAttribute('name', 'quick_key'); // Đổi tên để gửi lên server
                    quickKeySelect.removeAttribute('name'); // Xóa tên để không gửi giá trị select
                } else {
                    newQuickKeyInput.classList.add('hidden');
                    newQuickKeyInput.removeAttribute('name'); // Xóa tên
                    quickKeySelect.setAttribute('name', 'quick_key'); // Đổi tên lại
                }
            });

            // Nếu có lỗi validation và old('quick_key') là 'custom_key', hiển thị lại input text
            @if(old('quick_key') === 'custom_key')
                newQuickKeyInput.classList.remove('hidden');
                newQuickKeyInput.setAttribute('name', 'quick_key');
                quickKeySelect.removeAttribute('name');
            @endif
        });
    </script>
    @endpush
</x-app-layout>