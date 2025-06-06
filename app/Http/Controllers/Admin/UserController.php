<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource (all users except current admin).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Lấy tất cả người dùng, trừ tài khoản admin hiện tại đang đăng nhập.
        // Điều này ngăn admin tự ý chỉnh sửa hoặc xóa tài khoản của mình trên danh sách.
        $users = User::where('id', '!=', Auth::id())->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Truyền một instance User mới để form có thể dùng thuộc tính
        // và tránh lỗi "Attempt to read property on null" khi tạo mới.
        return view('admin.users.create', ['user' => new User()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào.
        // Loại bỏ rule 'boolean' cho 'is_admin' và 'is_active'.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // 'is_admin' => 'boolean', // <-- BỎ DÒNG NÀY HOẶC COMMENT LẠI
            // 'is_active' => 'boolean', // <-- BỎ DÒNG NÀY HOẶC COMMENT LẠI
        ]);

        // Hashing mật khẩu trước khi lưu vào database
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Xử lý các checkbox: nếu checkbox không được chọn, nó sẽ không có trong request,
        // do đó cần kiểm tra bằng has() để gán giá trị boolean.
        $validatedData['is_admin'] = $request->has('is_admin');
        $validatedData['is_active'] = $request->has('is_active');

        // Tạo người dùng mới
        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được thêm thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(User $user)
    {
        // Ngăn admin tự chỉnh sửa tài khoản của chính mình qua trang này
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể chỉnh sửa tài khoản của chính mình.');
        }
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Ngăn admin tự cập nhật tài khoản của chính mình qua trang này
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể cập nhật tài khoản của chính mình.');
        }

        // Xác thực dữ liệu đầu vào.
        // Loại bỏ rule 'boolean' cho 'is_admin' và 'is_active'.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Email phải duy nhất nhưng bỏ qua email của chính người dùng này
            ],
            'password' => 'nullable|string|min:8|confirmed', // Mật khẩu là tùy chọn khi cập nhật, và phải được xác nhận nếu có
            // 'is_admin' => 'boolean', // <-- BỎ DÒNG NÀY HOẶC COMMENT LẠI
            // 'is_active' => 'boolean', // <-- BỎ DÒNG NÀY HOẶC COMMENT LẠI
        ]);

        // Chỉ cập nhật mật khẩu nếu trường password không rỗng
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            // Nếu mật khẩu trống, loại bỏ nó khỏi dữ liệu đã xác thực để không cập nhật
            unset($validatedData['password']);
        }

        // Xử lý các checkbox cho is_admin và is_active
        $validatedData['is_admin'] = $request->has('is_admin');
        $validatedData['is_active'] = $request->has('is_active');

        // Cập nhật thông tin người dùng
        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Thông tin người dùng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Ngăn admin tự xóa tài khoản của chính mình
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        // Xóa người dùng
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được xóa thành công!');
    }

    /**
     * Chuyển đổi trạng thái kích hoạt (is_active) của người dùng.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive(User $user)
    {
        // Ngăn admin tự khóa/mở khóa tài khoản của chính mình
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể tự khóa hoặc mở khóa tài khoản của mình.');
        }

        $user->is_active = !$user->is_active; // Đảo ngược trạng thái
        $user->save(); // Lưu thay đổi vào database

        $status = $user->is_active ? 'mở khóa' : 'khóa';
        return redirect()->route('admin.users.index')->with('success', 'Đã ' . $status . ' người dùng ' . $user->name . ' thành công.');
    }
}
