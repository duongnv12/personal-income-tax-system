@props(['dependent' => null])

<div class="space-y-6">
    <div>
        <label for="full_name" class="block text-sm font-medium text-gray-700">Họ và Tên</label>
        <input type="text" name="full_name" id="full_name" required
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('full_name') border-red-500 @enderror"
               value="{{ old('full_name', $dependent?->full_name ?? '') }}"> {{-- THAY ĐỔI Ở ĐÂY --}}
        @error('full_name')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="dob" class="block text-sm font-medium text-gray-700">Ngày sinh</label>
        <input type="date" name="dob" id="dob" required
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('dob') border-red-500 @enderror"
               value="{{ old('dob', $dependent?->dob?->format('Y-m-d') ?? '') }}"> {{-- THAY ĐỔI Ở ĐÂY --}}
        @error('dob')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="relationship" class="block text-sm font-medium text-gray-700">Quan hệ</label>
        <select name="relationship" id="relationship" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('relationship') border-red-500 @enderror">
            <option value="">Chọn quan hệ</option>
            <option value="con" {{ old('relationship', $dependent?->relationship ?? '') == 'con' ? 'selected' : '' }}>Con</option> {{-- THAY ĐỔI Ở ĐÂY --}}
            <option value="cha" {{ old('relationship', $dependent?->relationship ?? '') == 'cha' ? 'selected' : '' }}>Cha</option> {{-- THAY ĐỔI Ở ĐÂY --}}
            <option value="me" {{ old('relationship', $dependent?->relationship ?? '') == 'me' ? 'selected' : '' }}>Mẹ</option> {{-- THAY ĐỔI Ở ĐÂY --}}
            <option value="vo" {{ old('relationship', $dependent?->relationship ?? '') == 'vo' ? 'selected' : '' }}>Vợ</option> {{-- THAY ĐỔI Ở ĐÂY --}}
            <option value="chong" {{ old('relationship', $dependent?->relationship ?? '') == 'chong' ? 'selected' : '' }}>Chồng</option> {{-- THAY ĐỔI Ở ĐÂY --}}
            <option value="anh_chi_em" {{ old('relationship', $dependent?->relationship ?? '') == 'anh_chi_em' ? 'selected' : '' }}>Anh/Chị/Em ruột</option> {{-- THAY ĐỔI Ở ĐÂY --}}
            <option value="khac" {{ old('relationship', $dependent?->relationship ?? '') == 'khac' ? 'selected' : '' }}>Khác</option> {{-- THAY ĐỔI Ở ĐÂY --}}
        </select>
        @error('relationship')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="identification_number" class="block text-sm font-medium text-gray-700">Số CCCD/CMND (Không bắt buộc)</label>
        <input type="text" name="identification_number" id="identification_number"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('identification_number') border-red-500 @enderror"
               value="{{ old('identification_number', $dependent?->identification_number ?? '') }}"> {{-- THAY ĐỔI Ở ĐÂY --}}
        @error('identification_number')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="registration_date" class="block text-sm font-medium text-gray-700">Ngày đăng ký giảm trừ (Không bắt buộc)</label>
        <input type="date" name="registration_date" id="registration_date"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('registration_date') border-red-500 @enderror"
               value="{{ old('registration_date', $dependent?->registration_date?->format('Y-m-d') ?? '') }}"> {{-- THAY ĐỔI Ở ĐÂY --}}
        @error('registration_date')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center">
        <input type="checkbox" name="is_disabled" id="is_disabled" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               {{ old('is_disabled', $dependent?->is_disabled ?? false) ? 'checked' : '' }}> {{-- THAY ĐỔI Ở ĐÂY --}}
        <label for="is_disabled" class="ml-2 block text-sm text-gray-900">Là người khuyết tật / mất khả năng lao động</label>
        @error('is_disabled')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>