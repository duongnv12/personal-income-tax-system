<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxParameter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxParameterController extends Controller
{
    /**
     * Display a listing of the tax parameters.
     */
    public function index()
    {
        $taxParameters = TaxParameter::orderBy('param_key')->get();
        return view('admin.tax-parameters.index', compact('taxParameters'));
    }

    /**
     * Show the form for editing the specified tax parameter.
     */
    public function edit(TaxParameter $taxParameter)
    {
        return view('admin.tax-parameters.edit', compact('taxParameter'));
    }

    /**
     * Update the specified tax parameter in storage.
     */
    public function update(Request $request, TaxParameter $taxParameter)
    {
        $validatedData = $request->validate([
            'param_value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $taxParameter->update($validatedData);

        return redirect()->route('admin.tax-parameters.index')->with('success', 'Tham số thuế đã được cập nhật thành công.');
    }
}