<?php

namespace App\Http\Controllers;

use App\Models\TaxBracket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TaxBracketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taxBrackets = TaxBracket::orderBy('level')->get();
        return view('tax-brackets.index', compact('taxBrackets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tax-brackets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'level' => 'required|integer|min:1|unique:tax_brackets,level',
                'income_from' => 'required|numeric|min:0',
                'income_to' => 'nullable|numeric|gt:income_from', // income_to phải lớn hơn income_from nếu có
                'tax_rate' => 'required|numeric|min:0|max:1',
            ], [
                'level.unique' => 'Bậc thuế này đã tồn tại. Vui lòng chọn bậc khác.',
                'income_to.gt' => 'Thu nhập đến phải lớn hơn Thu nhập từ.',
                'required' => 'Trường :attribute là bắt buộc.',
                'integer' => 'Trường :attribute phải là số nguyên.',
                'numeric' => 'Trường :attribute phải là một số.',
                'min' => 'Trường :attribute phải lớn hơn hoặc bằng :min.',
                'max' => 'Trường :attribute phải nhỏ hơn hoặc bằng :max.',
            ]);

            // Kiểm tra logic các khoảng income_from, income_to để đảm bảo không chồng lấn
            // (Đơn giản hóa: chỉ kiểm tra trùng lặp level, logic chồng lấn phức tạp hơn cần thêm validation rule tùy chỉnh hoặc trong service)

            TaxBracket::create($validatedData);

            return redirect()->route('tax-brackets.index')->with('success', 'Đã thêm bậc thuế thành công.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxBracket $taxBracket)
    {
        return view('tax-brackets.edit', compact('taxBracket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxBracket $taxBracket)
    {
        try {
            $validatedData = $request->validate([
                'level' => ['required', 'integer', 'min:1', Rule::unique('tax_brackets')->ignore($taxBracket->id)],
                'income_from' => 'required|numeric|min:0',
                'income_to' => 'nullable|numeric|gt:income_from',
                'tax_rate' => 'required|numeric|min:0|max:1',
            ], [
                'level.unique' => 'Bậc thuế này đã tồn tại. Vui lòng chọn bậc khác.',
                'income_to.gt' => 'Thu nhập đến phải lớn hơn Thu nhập từ.',
                'required' => 'Trường :attribute là bắt buộc.',
                'integer' => 'Trường :attribute phải là số nguyên.',
                'numeric' => 'Trường :attribute phải là một số.',
                'min' => 'Trường :attribute phải lớn hơn hoặc bằng :min.',
                'max' => 'Trường :attribute phải nhỏ hơn hoặc bằng :max.',
            ]);

            // Tương tự, kiểm tra logic chồng lấn nếu cần

            $taxBracket->update($validatedData);

            return redirect()->route('tax-brackets.index')->with('success', 'Đã cập nhật bậc thuế thành công.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxBracket $taxBracket)
    {
        try {
            $taxBracket->delete();
            return redirect()->route('tax-brackets.index')->with('success', 'Đã xóa bậc thuế thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể xóa bậc thuế: ' . $e->getMessage());
        }
    }
}