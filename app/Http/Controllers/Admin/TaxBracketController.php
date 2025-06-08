<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxBracket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxBracketController extends Controller
{
    /**
     * Display a listing of the tax brackets.
     */
    public function index()
    {
        $taxBrackets = TaxBracket::orderBy('level')->get();
        return view('admin.tax-brackets.index', compact('taxBrackets'));
    }

    /**
     * Show the form for creating a new tax bracket.
     */
    public function create()
    {
        return view('admin.tax-brackets.create');
    }

    /**
     * Store a newly created tax bracket in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'level' => 'required|integer|min:1|unique:tax_brackets,level',
            'income_from' => 'required|numeric|min:0',
            'income_to' => 'nullable|numeric|gt:income_from', // income_to có thể null (bậc cuối), nhưng nếu có thì phải lớn hơn income_from
            'tax_rate' => 'required|numeric|min:0|max:1', // Tỷ lệ từ 0 đến 1 (ví dụ 0.05 cho 5%)
        ], [
            'level.unique' => 'Cấp độ này đã tồn tại.',
            'income_to.gt' => 'Thu nhập đến phải lớn hơn thu nhập từ.',
        ]);

        TaxBracket::create($validatedData);

        return redirect()->route('admin.tax-brackets.index')->with('success', 'Bậc thuế đã được thêm thành công.');
    }

    /**
     * Show the form for editing the specified tax bracket.
     */
    public function edit(TaxBracket $taxBracket)
    {
        return view('admin.tax-brackets.edit', compact('taxBracket'));
    }

    /**
     * Update the specified tax bracket in storage.
     */
    public function update(Request $request, TaxBracket $taxBracket)
    {
        $validatedData = $request->validate([
            'level' => ['required', 'integer', 'min:1', Rule::unique('tax_brackets')->ignore($taxBracket->id)],
            'income_from' => 'required|numeric|min:0',
            'income_to' => 'nullable|numeric|gt:income_from',
            'tax_rate' => 'required|numeric|min:0|max:1',
        ], [
            'level.unique' => 'Cấp độ này đã tồn tại.',
            'income_to.gt' => 'Thu nhập đến phải lớn hơn thu nhập từ.',
        ]);

        $taxBracket->update($validatedData);

        return redirect()->route('admin.tax-brackets.index')->with('success', 'Bậc thuế đã được cập nhật thành công.');
    }

    /**
     * Remove the specified tax bracket from storage.
     */
    public function destroy(TaxBracket $taxBracket)
    {
        $taxBracket->delete();
        return redirect()->route('admin.tax-brackets.index')->with('success', 'Bậc thuế đã được xóa thành công.');
    }
}