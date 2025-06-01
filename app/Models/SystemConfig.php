<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'effective_date',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'value' => 'float', // Cast value sang float hoặc decimal
    ];
}