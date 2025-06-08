<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateIncomeSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && $this->route('income_source')->user_id === Auth::id();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tax_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('income_sources')->ignore($this->route('income_source')->id),
            ],
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