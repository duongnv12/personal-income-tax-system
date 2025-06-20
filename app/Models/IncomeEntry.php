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
        'year',
        'month',
        'entry_type',
        'gross_income',
        'net_income',
        'tax_paid',
        'bhxh_deduction',
        'other_deductions',
        'income_type'
    ];

    protected $casts = [
        'gross_income' => 'float',
        'net_income' => 'float',
        'tax_paid' => 'float',
        'bhxh_deduction' => 'float',
        'other_deductions' => 'float',
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