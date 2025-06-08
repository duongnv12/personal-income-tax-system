<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreIncomeSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tax_code' => 'nullable|string|max:255|unique:income_sources,tax_code',
            'address' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên nguồn thu nhập không được để trống.',
            'tax_code.unique' => 'Mã số thuế này đã được sử dụng cho nguồn thu nhập khác.',
        ];
    }
}