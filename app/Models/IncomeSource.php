<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'income_type',
        'tax_code',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomeEntries()
    {
        return $this->hasMany(IncomeEntry::class);
    }
}