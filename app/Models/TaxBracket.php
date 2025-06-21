<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxBracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'min_income',
        'max_income',
        'tax_rate',
        'effective_date',
    ];

    protected $casts = [
        'min_income' => 'integer',
        'max_income' => 'integer',
        'tax_rate' => 'float', // Cast tax_rate sang float
        'effective_date' => 'date',
    ];
}