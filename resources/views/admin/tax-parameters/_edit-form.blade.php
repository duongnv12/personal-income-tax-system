<form method="POST" action="{{ route('admin.tax-parameters.update', $taxParameter) }}">
    @csrf
    @method('PATCH')
    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">
        <i class="fa-solid fa-cog mr-2 text-blue-600"></i> Chỉnh sửa Tham số Thuế
    </h3>
    <div class="space-y-6">
        <div class="mb-4">
            <x-input-label for="param_key" :value="__('Khóa tham số')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="param_key" type="text" class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-sm py-2 cursor-not-allowed" :value="$taxParameter->param_key" readonly />
            <p class="mt-2 text-sm text-gray-500">Khóa tham số là duy nhất và không thể thay đổi.</p>
        </div>
        <div>
            <x-input-label for="param_value" :value="__('Giá trị tham số')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="param_value" name="param_value" type="number" step="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2" :value="old('param_value', $taxParameter->param_value)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('param_value')" />
        </div>
        <div class="mt-4">
            <x-input-label for="description" :value="__('Mô tả')" class="text-sm font-medium text-gray-700" />
            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-2 px-3">{{ old('description', $taxParameter->description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>
    </div>
    <div class="flex items-center justify-end mt-8">
        <button type="button" class="btn-close-modal inline-flex items-center px-5 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md mr-4">
            {{ __('Hủy') }}
        </button>
        <x-primary-button class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 shadow-md">
            <i class="fa-solid fa-save mr-2"></i>
            {{ __('Cập nhật') }}
        </x-primary-button>
    </div>
</form> 