{{-- resources/views/admin/tax_brackets/form.blade.php --}}

@php
    // Determine if we are in edit mode (true if $taxBracket exists and has been persisted)
    $isEdit = isset($taxBracket) && $taxBracket->exists;
@endphp

<div class="mb-4">
    <x-input-label for="level" :value="__('Cấp bậc')" />
    <x-text-input id="level" name="level" type="number" class="mt-1 block w-full"
                  :value="old('level', $isEdit ? $taxBracket->level : '')"
                  required autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('level')" />
</div>

<div class="mb-4">
    <x-input-label for="min_income" :value="__('Thu nhập tối thiểu')" />
    <x-text-input id="min_income" name="min_income" type="number" step="any" class="mt-1 block w-full"
                  :value="old('min_income', $isEdit ? $taxBracket->min_income : '')"
                  required />
    <x-input-error class="mt-2" :messages="$errors->get('min_income')" />
</div>

<div class="mb-4">
    <x-input-label for="max_income" :value="__('Thu nhập tối đa (để trống nếu là mức cuối)')" />
    <x-text-input id="max_income" name="max_income" type="number" step="any" class="mt-1 block w-full"
                  :value="old('max_income', $isEdit ? $taxBracket->max_income : '')" />
    <x-input-error class="mt-2" :messages="$errors->get('max_income')" />
</div>

<div class="mb-4">
    <x-input-label for="tax_rate" :value="__('Tỷ lệ thuế (%)')" />
    {{-- THIS IS THE LINE THAT LIKELY CAUSED THE ERROR --}}
    <x-text-input id="tax_rate" name="tax_rate" type="number" step="0.01" class="mt-1 block w-full"
                  :value="old('tax_rate', $isEdit ? ($taxBracket->tax_rate * 100) : '')"
                  required />
    <x-input-error class="mt-2" :messages="$errors->get('tax_rate')" />
</div>

<div class="mb-4">
    <x-input-label for="description" :value="__('Mô tả')" />
    <x-textarea id="description" name="description" class="mt-1 block w-full"
                  autocomplete="description">{{ old('description', $isEdit ? $taxBracket->description : '') }}</x-textarea>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>

<div class="mb-4">
    <x-input-label for="effective_date" :value="__('Ngày hiệu lực')" />
    <x-text-input id="effective_date" name="effective_date" type="date" class="mt-1 block w-full"
                  :value="old('effective_date', $isEdit && $taxBracket->effective_date ? $taxBracket->effective_date->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d'))"
                  required />
    <x-input-error class="mt-2" :messages="$errors->get('effective_date')" />
</div>