<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'declaration_month',
        'gross_salary',
        'other_taxable_income',
        'non_taxable_income',
        'social_insurance_contribution',
        'deduction_charity',
        'tax_deducted_at_source',
        'calculated_tax',
        'net_salary',
    ];

    protected $casts = [
        'declaration_month' => 'date', // Để đảm bảo đây là đối tượng Carbon Date
        'gross_salary' => 'integer',
        'other_taxable_income' => 'integer',
        'non_taxable_income' => 'integer',
        'social_insurance_contribution' => 'integer',
        'deduction_charity' => 'integer',
        'tax_deducted_at_source' => 'integer',
        'calculated_tax' => 'integer',
        'net_salary' => 'integer',
    ];

    // Định nghĩa mối quan hệ: Một khai báo thu nhập thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}