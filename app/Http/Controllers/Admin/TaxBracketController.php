<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxBracket; // Make sure this is imported
use Illuminate\Http\Request;
use Carbon\Carbon; // Make sure this is imported

class TaxBracketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all tax brackets, ordered by effective date and then by min_income
        $taxBrackets = TaxBracket::orderBy('effective_date', 'desc')
                                 ->orderBy('min_income', 'asc')
                                 ->get();

        return view('admin.tax_brackets.index', compact('taxBrackets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Pass a new TaxBracket instance to the view to avoid null errors
        return view('admin.tax_brackets.create', ['taxBracket' => new TaxBracket()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => ['required', 'integer', 'min:1', 'unique:tax_brackets,level,NULL,id,effective_date,' . $request->effective_date], // Ensure level is unique for a given effective_date
            'min_income' => ['required', 'numeric', 'min:0'],
            'max_income' => ['nullable', 'numeric', 'gt:min_income'], // Max income must be greater than min income
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:1'], // Tax rate between 0 and 1
            'description' => ['nullable', 'string', 'max:1000'],
            'effective_date' => ['required', 'date'],
        ]);

        TaxBracket::create($validated);

        return redirect()->route('admin.tax_brackets.index')->with('success', 'Bậc thuế đã được thêm thành công.');
    }

    // The 'show' method is typically excluded for tax brackets, as per previous instructions.

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxBracket $taxBracket)
    {
        // Laravel's Route Model Binding automatically finds the TaxBracket or throws a 404.
        return view('admin.tax_brackets.edit', compact('taxBracket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxBracket $taxBracket)
    {
        $validated = $request->validate([
            // Level uniqueness check: ensure it's unique for a given effective_date, ignoring current record
            'level' => ['required', 'integer', 'min:1', 'unique:tax_brackets,level,' . $taxBracket->id . ',id,effective_date,' . $request->effective_date],
            'min_income' => ['required', 'numeric', 'min:0'],
            'max_income' => ['nullable', 'numeric', 'gt:min_income'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'description' => ['nullable', 'string', 'max:1000'],
            'effective_date' => ['required', 'date'],
        ]);

        $taxBracket->update($validated);

        return redirect()->route('admin.tax_brackets.index')->with('success', 'Bậc thuế đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxBracket $taxBracket)
    {
        $taxBracket->delete();
        return redirect()->route('admin.tax_brackets.index')->with('success', 'Bậc thuế đã được xóa thành công.');
    }
}