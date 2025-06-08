<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDependentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Chỉ cho phép người dùng đã đăng nhập và được xác minh thực hiện yêu cầu
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'relationship' => 'required|string|max:255',
            'tax_code' => 'nullable|string|max:255|unique:dependents,tax_code',
            'deduction_start_date' => 'nullable|date',
            'deduction_end_date' => 'nullable|date|after_or_equal:deduction_start_date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên người phụ thuộc không được để trống.',
            'date_of_birth.required' => 'Ngày sinh không được để trống.',
            'date_of_birth.date' => 'Ngày sinh phải là định dạng ngày hợp lệ.',
            'date_of_birth.before' => 'Ngày sinh phải là một ngày trong quá khứ.',
            'relationship.required' => 'Mối quan hệ không được để trống.',
            'tax_code.unique' => 'Mã số thuế này đã được sử dụng cho người phụ thuộc khác.',
            'deduction_end_date.after_or_equal' => 'Ngày kết thúc giảm trừ phải sau hoặc bằng ngày bắt đầu giảm trừ.',
        ];
    }
}