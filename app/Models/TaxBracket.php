<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxBracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'income_from',
        'income_to',
        'tax_rate',
        'effective_date',
    ];

    protected $casts = [
        'income_from' => 'integer',
        'income_to' => 'integer',
        'tax_rate' => 'float', // Cast tax_rate sang float
        'effective_date' => 'date',
    ];
}