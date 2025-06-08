<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Tham số Thuế') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Cập nhật các Tham số tính toán thuế TNCN</h3>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tax_parameters.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="gt_ban_than" :value="__('Giảm trừ bản thân (VNĐ/tháng)')" />
                                <x-text-input id="gt_ban_than" name="gt_ban_than" type="number" step="any" min="0" class="mt-1 block w-full"
                                              :value="old('gt_ban_than', $parametersMap->get('gt_ban_than')->param_value ?? 0)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('gt_ban_than')" />
                            </div>

                            <div>
                                <x-input-label for="gt_nguoi_phu_thuoc" :value="__('Giảm trừ người phụ thuộc (VNĐ/tháng)')" />
                                <x-text-input id="gt_nguoi_phu_thuoc" name="gt_nguoi_phu_thuoc" type="number" step="any" min="0" class="mt-1 block w-full"
                                              :value="old('gt_nguoi_phu_thuoc', $parametersMap->get('gt_nguoi_phu_thuoc')->param_value ?? 0)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('gt_nguoi_phu_thuoc')" />
                            </div>

                            <div>
                                <x-input-label for="bh_tl_tong" :value="__('Tỷ lệ Bảo hiểm bắt buộc tổng cộng (%)')" />
                                <x-text-input id="bh_tl_tong" name="bh_tl_tong" type="number" step="0.0001" min="0" max="1" class="mt-1 block w-full"
                                              :value="old('bh_tl_tong', $parametersMap->get('bh_tl_tong')->param_value ?? 0)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('bh_tl_tong')" />
                                <p class="text-sm text-gray-500 mt-1">Ví dụ: 0.105 cho 10.5% (8% BHXH, 1.5% BHYT, 1% BHTN)</p>
                            </div>

                            <div>
                                <x-input-label for="bh_tran_luong" :value="__('Trần lương đóng Bảo hiểm (VNĐ/tháng)')" />
                                <x-text-input id="bh_tran_luong" name="bh_tran_luong" type="number" step="any" min="0" class="mt-1 block w-full"
                                              :value="old('bh_tran_luong', $parametersMap->get('bh_tran_luong')->param_value ?? 0)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('bh_tran_luong')" />
                                <p class="text-sm text-gray-500 mt-1">Ví dụ: 29,800,000 VNĐ (20 lần lương cơ sở)</p>
                            </div>

                            </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Cập nhật Tham số') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>