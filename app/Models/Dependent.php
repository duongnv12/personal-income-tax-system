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
        'registration_date', 
        'deactivation_date', 
        'status', 
    ];

    protected $casts = [
        'dob' => 'date',
        'registration_date' => 'datetime', 
        'deactivation_date' => 'datetime', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}