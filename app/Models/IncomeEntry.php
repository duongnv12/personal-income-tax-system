<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'income_source_id',
        'income_type', // Thêm vào đây
        'year',
        'month',
        'entry_type',
        'gross_income',
        'bhxh_deduction',
        'other_deductions',
        'tax_paid',
        'net_income',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomeSource()
    {
        return $this->belongsTo(IncomeSource::class);
    }
}