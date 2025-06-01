@props(['user' => null])

<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Tên</label>
        <input type="text" name="name" id="name" required
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('name') border-red-500 @enderror"
               value="{{ old('name', $user->name ?? '') }}">
        @error('name')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" required
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('email') border-red-500 @enderror"
               value="{{ old('email', $user->email ?? '') }}">
        @error('email')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu (Để trống nếu không đổi)</label>
        <input type="password" name="password" id="password"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('password') border-red-500 @enderror">
        @error('password')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận Mật khẩu</label>
        <input type="password" name="password_confirmation" id="password_confirmation"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
    </div>

    <div class="flex items-center">
        <input type="checkbox" name="is_admin" id="is_admin" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               {{ old('is_admin', $user->is_admin ?? false) ? 'checked' : '' }}>
        <label for="is_admin" class="ml-2 text-sm font-medium text-gray-700">Là Quản trị viên (Admin)</label>
        @error('is_admin')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center">
        <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Tài khoản Hoạt động</label>
        @error('is_active')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>