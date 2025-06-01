<?php

namespace App\Http\Controllers;

use App\Models\Dependent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DependentController extends Controller
{
    /**
     * Hiển thị danh sách người phụ thuộc của người dùng hiện tại.
     */
    public function index()
    {
        $user = Auth::user();
        $dependents = $user->dependents()->orderBy('dob')->get(); // Lấy người phụ thuộc của người dùng đang đăng nhập

        return view('dependents.index', compact('dependents'));
    }

    /**
     * Hiển thị form để thêm người phụ thuộc mới.
     */
    public function create()
    {
        return view('dependents.create');
    }

    /**
     * Lưu người phụ thuộc mới vào database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before_or_equal:' . Carbon::now()->format('Y-m-d'), // Ngày sinh không thể sau ngày hiện tại
            'relationship' => 'required|string|max:100', // Ví dụ: 'con', 'cha', 'mẹ', 'vợ', 'chồng'
            'identification_number' => [ // Số CCCD/CMND
                'nullable',
                'string',
                'max:20',
                // Đảm bảo mã số định danh là duy nhất trên toàn hệ thống
                Rule::unique('dependents', 'identification_number'),
            ],
            'registration_date' => 'nullable|date|before_or_equal:' . Carbon::now()->format('Y-m-d'),
            'is_disabled' => 'boolean',
        ]);

        $user->dependents()->create($request->all());

        return redirect()->route('dependents.index')->with('success', 'Đã thêm người phụ thuộc thành công!');
    }

    /**
     * Hiển thị form để chỉnh sửa thông tin người phụ thuộc.
     */
    public function edit(Dependent $dependent)
    {
        // Đảm bảo người dùng chỉ có thể chỉnh sửa người phụ thuộc của mình
        if ($dependent->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập người phụ thuộc này.');
        }

        return view('dependents.edit', compact('dependent'));
    }

    /**
     * Cập nhật thông tin người phụ thuộc trong database.
     */
    public function update(Request $request, Dependent $dependent)
    {
        // Đảm bảo người dùng chỉ có thể cập nhật người phụ thuộc của mình
        if ($dependent->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền cập nhật người phụ thuộc này.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before_or_equal:' . Carbon::now()->format('Y-m-d'),
            'relationship' => 'required|string|max:100',
            'identification_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('dependents', 'identification_number')->ignore($dependent->id), // Bỏ qua chính bản thân nó khi cập nhật
            ],
            'registration_date' => 'nullable|date|before_or_equal:' . Carbon::now()->format('Y-m-d'),
            'is_disabled' => 'boolean',
        ]);

        $dependent->update($request->all());

        return redirect()->route('dependents.index')->with('success', 'Đã cập nhật người phụ thuộc thành công!');
    }

    /**
     * Xóa người phụ thuộc khỏi database.
     */
    public function destroy(Dependent $dependent)
    {
        // Đảm bảo người dùng chỉ có thể xóa người phụ thuộc của mình
        if ($dependent->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa người phụ thuộc này.');
        }

        $dependent->delete();

        return redirect()->route('dependents.index')->with('success', 'Đã xóa người phụ thuộc thành công!');
    }
}