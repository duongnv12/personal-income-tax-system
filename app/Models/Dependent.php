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
        'identification_number',
        'relationship',
        'gender',
        'registration_date', // Thêm vào đây
        'deactivation_date', // Thêm vào đây
        'status', // Giữ nguyên
    ];

    protected $casts = [
        'dob' => 'date',
        'registration_date' => 'date', // Cast as date
        'deactivation_date' => 'date', // Cast as date
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}