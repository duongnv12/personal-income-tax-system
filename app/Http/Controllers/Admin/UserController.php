<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource (all users except current admin).
     */
    public function index()
    {
        // Lấy tất cả người dùng, trừ tài khoản admin hiện tại đang đăng nhập
        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['is_admin'] = $request->has('is_admin'); // Nếu checkbox được check thì true, ngược lại false
        $validatedData['is_active'] = $request->has('is_active'); // Tương tự cho is_active

        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được thêm thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Admin không thể chỉnh sửa tài khoản của chính mình thông qua trang này
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể chỉnh sửa tài khoản của chính mình.');
        }
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Admin không thể chỉnh sửa tài khoản của chính mình
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể cập nhật tài khoản của chính mình.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed', // Mật khẩu có thể không đổi
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']); // Không cập nhật mật khẩu nếu trống
        }

        $validatedData['is_admin'] = $request->has('is_admin');
        $validatedData['is_active'] = $request->has('is_active');

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Thông tin người dùng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Admin không thể xóa tài khoản của chính mình
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

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
        // Không cho phép tự khóa tài khoản của chính mình
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể tự khóa hoặc mở khóa tài khoản của mình.');
        }

        $user->is_active = !$user->is_active; // Đảo ngược trạng thái
        $user->save();

        $status = $user->is_active ? 'mở khóa' : 'khóa';
        return redirect()->back()->with('success', 'Đã ' . $status . ' người dùng ' . $user->name . ' thành công.');
    }
}