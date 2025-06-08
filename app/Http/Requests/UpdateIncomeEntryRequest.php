<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateIncomeEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && $this->route('income_entry')->user_id === Auth::id();
    }

    public function rules(): array
    {
        return [
            'income_source_id' => [
                'required',
                'exists:income_sources,id',
                Rule::exists('income_sources', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'month' => 'nullable|integer|min:1|max:12',
            'entry_type' => 'required|in:monthly,yearly',
            'gross_income' => 'required|numeric|min:0',
            'net_income' => 'nullable|numeric|min:0',
            'tax_paid' => 'nullable|numeric|min:0',
            'bhxh_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'income_source_id.required' => 'Nguồn thu nhập không được để trống.',
            'income_source_id.exists' => 'Nguồn thu nhập không hợp lệ.',
            'year.required' => 'Năm thu nhập không được để trống.',
            'year.integer' => 'Năm thu nhập phải là số nguyên.',
            'month.integer' => 'Tháng thu nhập phải là số nguyên.',
            'month.min' => 'Tháng thu nhập phải từ 1 đến 12.',
            'month.max' => 'Tháng thu nhập phải từ 1 đến 12.',
            'entry_type.required' => 'Loại nhập liệu không được để trống.',
            'entry_type.in' => 'Loại nhập liệu không hợp lệ.',
            'gross_income.required' => 'Tổng thu nhập Gross không được để trống.',
            'gross_income.numeric' => 'Tổng thu nhập Gross phải là số.',
            'gross_income.min' => 'Tổng thu nhập Gross không được âm.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->entry_type === 'monthly' && empty($this->month)) {
                $validator->errors()->add('month', 'Tháng không được để trống khi nhập liệu theo tháng.');
            }
            if ($this->entry_type === 'yearly' && !empty($this->month)) {
                $validator->errors()->add('month', 'Tháng phải để trống khi nhập liệu theo năm.');
            }
        });
    }
}