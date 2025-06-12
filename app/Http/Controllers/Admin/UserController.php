<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class UserController extends Controller
{
    /**
     * Display a listing of the users, excluding the currently logged-in user.
     */
    public function index()
    {
        // Lấy ID của người dùng hiện tại
        $loggedInUserId = Auth::id();

        // Truy vấn tất cả người dùng trừ người dùng hiện tại, sắp xếp và phân trang
        $users = User::where('id', '!=', $loggedInUserId)
                     ->orderBy('created_at', 'asc')
                     ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Thêm validation unique cho email khi tạo mới
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_admin' => ['boolean'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->has('is_admin'), // Gán giá trị từ checkbox
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng mới đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Ngăn không cho người dùng hiện tại chỉnh sửa tài khoản của chính mình thông qua trang admin
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể chỉnh sửa tài khoản của chính mình từ đây. Vui lòng sử dụng trang hồ sơ của bạn.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Ngăn không cho người dùng hiện tại cập nhật tài khoản của chính mình thông qua trang admin
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể cập nhật tài khoản của chính mình từ đây. Vui lòng sử dụng trang hồ sơ của bạn.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean', // Cho phép thay đổi quyền admin
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->is_admin = $request->has('is_admin'); // Nếu checkbox được check thì là true

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được cập nhật thành công.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Ngăn không cho người dùng hiện tại xóa tài khoản của chính mình
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình từ đây. Vui lòng sử dụng trang hồ sơ của bạn nếu bạn muốn xóa.');
        }

        // Xóa tất cả các dữ liệu liên quan trước khi xóa người dùng (tùy chọn)
        // Đảm bảo rằng bạn có các mối quan hệ (relationships) được định nghĩa trong User model
        // Ví dụ: public function incomeEntries() { return $this->hasMany(IncomeEntry::class); }
        // public function incomeSources() { return $this->hasMany(IncomeSource::class); }
        // public function dependents() { return $this->hasMany(Dependent::class); }

        try {
            // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
            \DB::transaction(function () use ($user) {
                // Xóa các dữ liệu liên quan. Tùy thuộc vào model của bạn, bạn có thể cần thêm các dòng này.
                // Ví dụ:
                // $user->incomeEntries()->delete();
                // $user->incomeSources()->delete();
                // $user->dependents()->delete();

                $user->delete();
            });
            return redirect()->route('admin.users.index')->with('success', 'Người dùng và tất cả dữ liệu liên quan đã được xóa.');

        } catch (\Exception $e) {
            // Ghi log lỗi nếu có
            \Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('admin.users.index')->with('error', 'Đã xảy ra lỗi khi xóa người dùng. Vui lòng thử lại.');
        }
    }
}

