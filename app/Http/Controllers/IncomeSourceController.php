<?php

namespace App\Http\Controllers;

use App\Models\IncomeSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IncomeSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $query = IncomeSource::query()->where('user_id', auth()->id());

    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('description', 'like', "%$search%");
        });
    }

    $incomeSources = $query->paginate(10);

    return view('income-sources.index', compact('incomeSources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Định nghĩa các loại thu nhập có sẵn
        $incomeTypes = [
            'salary' => 'Tiền lương, tiền công',
            'business' => 'Kinh doanh (Doanh thu)',
            'investment' => 'Đầu tư (Lãi, Cổ tức, Chuyển nhượng vốn)',
            'other' => 'Khác',
        ];
        return view('income-sources.create', compact('incomeTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'income_type' => ['required', 'string', Rule::in(['salary', 'business', 'investment', 'other'])], // Thêm validation cho income_type
            'tax_code' => 'nullable|string|max:255|unique:income_sources,tax_code,' . Auth::id() . ',user_id', // Kiểm tra unique theo user_id
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Tên nguồn thu nhập là bắt buộc.',
            'income_type.required' => 'Loại thu nhập là bắt buộc.',
            'income_type.in' => 'Loại thu nhập không hợp lệ.',
            'tax_code.unique' => 'Mã số thuế này đã được đăng ký cho một nguồn thu nhập khác của bạn.',
        ]);

        Auth::user()->incomeSources()->create($validatedData);

        return redirect()->route('income-sources.index')->with('success', 'Đã thêm nguồn thu nhập thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomeSource $incomeSource)
    {
        if ($incomeSource->user_id !== Auth::id()) {
            abort(403);
        }
        $incomeTypes = [
            'salary' => 'Tiền lương, tiền công',
            'business' => 'Kinh doanh (Doanh thu)',
            'investment' => 'Đầu tư (Lãi, Cổ tức, Chuyển nhượng vốn)',
            'other' => 'Khác',
        ];
        return view('income-sources.edit', compact('incomeSource', 'incomeTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomeSource $incomeSource)
    {
        if ($incomeSource->user_id !== Auth::id()) {
            abort(403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'income_type' => ['required', 'string', Rule::in(['salary', 'business', 'investment', 'other'])], // Thêm validation cho income_type
            'tax_code' => ['nullable', 'string', 'max:255', Rule::unique('income_sources', 'tax_code')->ignore($incomeSource->id)],
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Tên nguồn thu nhập là bắt buộc.',
            'income_type.required' => 'Loại thu nhập là bắt buộc.',
            'income_type.in' => 'Loại thu nhập không hợp lệ.',
            'tax_code.unique' => 'Mã số thuế này đã được đăng ký cho một nguồn thu nhập khác của bạn.',
        ]);

        $incomeSource->update($validatedData);

        return redirect()->route('income-sources.index')->with('success', 'Đã cập nhật nguồn thu nhập thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomeSource $incomeSource)
    {
        if ($incomeSource->user_id !== Auth::id()) {
            abort(403);
        }

        // Trước khi xóa nguồn thu nhập, hãy đảm bảo xóa các khoản thu nhập liên quan
        $incomeSource->incomeEntries()->delete();
        $incomeSource->delete();

        return redirect()->route('income-sources.index')->with('success', 'Đã xóa nguồn thu nhập và các khoản thu nhập liên quan thành công.');
    }
    
}