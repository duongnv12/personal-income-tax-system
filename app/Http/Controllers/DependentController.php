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
     * Display a listing of the resource.
     */
    public function index()
    {
        $dependents = Auth::user()->dependents()->orderBy('created_at', 'desc')->get();
        return view('dependents.index', compact('dependents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lấy danh sách các mối quan hệ để populate dropdown
        $relationships = [
            'Con' => 'Con',
            'Vợ' => 'Vợ',
            'Chồng' => 'Chồng',
            'Cha' => 'Cha',
            'Mẹ' => 'Mẹ',
            'Khác' => 'Khác',
        ];
        return view('dependents.create', compact('relationships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before_or_equal:today',
            'identification_number' => 'required|string|max:255|unique:dependents',
            'relationship' => 'required|string|max:255',
            'gender' => 'required|in:Nam,Nữ,Khác',
            'registration_date' => 'required|date|before_or_equal:today', // Yêu cầu ngày đăng ký
            'deactivation_date' => 'nullable|date|after_or_equal:registration_date', // Ngày kết thúc phải sau hoặc bằng ngày đăng ký
            'status' => 'required|in:active,inactive',
        ], [
            'full_name.required' => 'Họ và tên người phụ thuộc là bắt buộc.',
            'dob.required' => 'Ngày sinh là bắt buộc.',
            'dob.date' => 'Ngày sinh không hợp lệ.',
            'dob.before_or_equal' => 'Ngày sinh không được sau ngày hiện tại.',
            'identification_number.required' => 'Số CCCD/CMND là bắt buộc.',
            'identification_number.unique' => 'Số CCCD/CMND này đã được đăng ký.',
            'relationship.required' => 'Mối quan hệ là bắt buộc.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'registration_date.required' => 'Ngày đăng ký là bắt buộc.',
            'registration_date.date' => 'Ngày đăng ký không hợp lệ.',
            'registration_date.before_or_equal' => 'Ngày đăng ký không được sau ngày hiện tại.',
            'deactivation_date.date' => 'Ngày kết thúc không hợp lệ.',
            'deactivation_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày đăng ký.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        Auth::user()->dependents()->create($validatedData);

        return redirect()->route('dependents.index')->with('success', 'Đã thêm người phụ thuộc thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dependent $dependent)
    {
        if ($dependent->user_id !== Auth::id()) {
            abort(403);
        }
        $relationships = [
            'Con' => 'Con',
            'Vợ' => 'Vợ',
            'Chồng' => 'Chồng',
            'Cha' => 'Cha',
            'Mẹ' => 'Mẹ',
            'Khác' => 'Khác',
        ];
        return view('dependents.edit', compact('dependent', 'relationships'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dependent $dependent)
    {
        if ($dependent->user_id !== Auth::id()) {
            abort(403);
        }

        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before_or_equal:today',
            'identification_number' => ['required', 'string', 'max:255', Rule::unique('dependents')->ignore($dependent->id)],
            'relationship' => 'required|string|max:255',
            'gender' => 'required|in:Nam,Nữ,Khác',
            'registration_date' => 'required|date|before_or_equal:today',
            'deactivation_date' => 'nullable|date|after_or_equal:registration_date',
            'status' => 'required|in:active,inactive',
        ], [
            'full_name.required' => 'Họ và tên người phụ thuộc là bắt buộc.',
            'dob.required' => 'Ngày sinh là bắt buộc.',
            'dob.date' => 'Ngày sinh không hợp lệ.',
            'dob.before_or_equal' => 'Ngày sinh không được sau ngày hiện tại.',
            'identification_number.required' => 'Số CCCD/CMND là bắt buộc.',
            'identification_number.unique' => 'Số CCCD/CMND này đã được đăng ký.',
            'relationship.required' => 'Mối quan hệ là bắt buộc.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'registration_date.required' => 'Ngày đăng ký là bắt buộc.',
            'registration_date.date' => 'Ngày đăng ký không hợp lệ.',
            'registration_date.before_or_equal' => 'Ngày đăng ký không được sau ngày hiện tại.',
            'deactivation_date.date' => 'Ngày kết thúc không hợp lệ.',
            'deactivation_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày đăng ký.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $dependent->update($validatedData);

        return redirect()->route('dependents.index')->with('success', 'Đã cập nhật người phụ thuộc thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dependent $dependent)
    {
        if ($dependent->user_id !== Auth::id()) {
            abort(403);
        }

        $dependent->delete();

        return redirect()->route('dependents.index')->with('success', 'Đã xóa người phụ thuộc thành công.');
    }
}