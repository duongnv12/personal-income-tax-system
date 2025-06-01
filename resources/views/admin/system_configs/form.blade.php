{{-- resources/views/admin/system_configs/form.blade.php --}}
@php
    $isEdit = isset($systemConfig) && $systemConfig->exists;
@endphp

<div class="mb-4">
    <x-input-label for="key" :value="__('Key Cấu hình')" />
    {{-- Sử dụng old() để giữ lại giá trị nếu có lỗi validate,
         sau đó đến $systemConfig->key nếu đang chỉnh sửa,
         cuối cùng là rỗng nếu tạo mới --}}
    <x-text-input id="key" name="key" type="text" class="mt-1 block w-full"
                  :value="old('key', $isEdit ? $systemConfig->key : '')"
                  required autofocus autocomplete="key" />
    <x-input-error class="mt-2" :messages="$errors->get('key')" />
</div>

<div class="mb-4">
    <x-input-label for="value" :value="__('Giá trị')" />
    <x-text-input id="value" name="value" type="number" step="any" class="mt-1 block w-full"
                  :value="old('value', $isEdit ? $systemConfig->value : '')"
                  required autocomplete="value" />
    <x-input-error class="mt-2" :messages="$errors->get('value')" />
</div>

<div class="mb-4">
    <x-input-label for="description" :value="__('Mô tả')" />
    {{-- Đảm bảo sử dụng id, name và truyền value vào --}}
    <x-textarea id="description" name="description" class="mt-1 block w-full"
                  :value="old('description', $isEdit ? $systemConfig->description : '')"
                  autocomplete="description"></x-textarea>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>

<div class="mb-4">
    <x-input-label for="effective_date" :value="__('Ngày hiệu lực')" />
    {{-- ĐÂY LÀ DÒNG BỊ LỖI TRƯỚC ĐÂY --}}
    <x-text-input id="effective_date" name="effective_date" type="date" class="mt-1 block w-full"
                  :value="old('effective_date', $isEdit ? $systemConfig->effective_date->format('Y-m-d') : Carbon\Carbon::now()->format('Y-m-d'))"
                  required autocomplete="effective_date" />
    <x-input-error class="mt-2" :messages="$errors->get('effective_date')" />
</div>