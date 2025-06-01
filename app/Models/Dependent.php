<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'dob',
        'relationship',
        'identification_number',
        'registration_date',
        'is_disabled',
    ];

    protected $casts = [
        'dob' => 'date',
        'registration_date' => 'date',
        'is_disabled' => 'boolean',
    ];

    // Định nghĩa mối quan hệ: Một người phụ thuộc thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}