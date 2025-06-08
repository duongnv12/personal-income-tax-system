<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Người Phụ Thuộc') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> {{-- Tăng chiều rộng max-w-md lên max-w-xl --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900">
                    <form method="POST" action="{{ route('dependents.update', $dependent) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input-label for="full_name" :value="__('Họ và tên người phụ thuộc')" />
                                <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('full_name', $dependent->full_name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
                            </div>

                            <div>
                                <x-input-label for="dob" :value="__('Ngày sinh')" />
                                <x-text-input id="dob" name="dob" type="date" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('dob', $dependent->dob ? $dependent->dob->format('Y-m-d') : '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('dob')" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="identification_number" :value="__('Số CCCD/CMND/Mã số thuế NPT')" />
                            <x-text-input id="identification_number" name="identification_number" type="text" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('identification_number', $dependent->identification_number)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('identification_number')" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                            <div>
                                <x-input-label for="relationship" :value="__('Mối quan hệ')" />
                                <select id="relationship" name="relationship" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Chọn mối quan hệ</option>
                                    @foreach ($relationships as $key => $value)
                                        <option value="{{ $key }}" {{ old('relationship', $dependent->relationship) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('relationship')" />
                            </div>

                            <div>
                                <x-input-label for="gender" :value="__('Giới tính')" />
                                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam" {{ old('gender', $dependent->gender) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ old('gender', $dependent->gender) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khác" {{ old('gender', $dependent->gender) == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                            <div>
                                <x-input-label for="registration_date" :value="__('Ngày đăng ký giảm trừ')" />
                                <x-text-input id="registration_date" name="registration_date" type="date" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('registration_date', $dependent->registration_date ? $dependent->registration_date->format('Y-m-d') : '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('registration_date')" />
                            </div>

                            <div>
                                <x-input-label for="deactivation_date" :value="__('Ngày kết thúc giảm trừ (Để trống nếu chưa kết thúc)')" />
                                <x-text-input id="deactivation_date" name="deactivation_date" type="date" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500" :value="old('deactivation_date', $dependent->deactivation_date ? $dependent->deactivation_date->format('Y-m-d') : '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('deactivation_date')" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="status" :value="__('Trạng thái')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="active" {{ old('status', $dependent->status) == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="inactive" {{ old('status', $dependent->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button class="ml-4 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                {{ __('Cập nhật') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>