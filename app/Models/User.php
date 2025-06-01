<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany; // Đảm bảo đã import

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Định nghĩa mối quan hệ với bảng 'dependents'.
     * Một người dùng có nhiều người phụ thuộc.
     */
    public function dependents(): HasMany
    {
        return $this->hasMany(Dependent::class);
    }

    /**
     * Lấy số lượng người phụ thuộc hợp lệ cho một tháng và năm cụ thể.
     * Người phụ thuộc được coi là hợp lệ nếu đáp ứng các tiêu chí giảm trừ.
     *
     * @param int $year Năm cần kiểm tra
     * @param int $month Tháng cần kiểm tra
     * @return int Số lượng người phụ thuộc hợp lệ
     */
    public function getValidDependentsCount(int $year, int $month): int
    {
        $count = 0;
        $checkDate = Carbon::create($year, $month, 1)->endOfMonth();

        foreach ($this->dependents as $dependent) {
            $isRegisteredInTime = true;
            if ($dependent->registration_date) {
                $isRegisteredInTime = $dependent->registration_date->lte($checkDate);
            }

            if (!$isRegisteredInTime) {
                continue;
            }

            switch ($dependent->relationship) {
                case 'con':
                    if ($dependent->dob) {
                        $ageAtCheckDate = $dependent->dob->diffInYears($checkDate);
                        if ($ageAtCheckDate < 18) {
                            $count++;
                        }
                    }
                    break;
                case 'vo':
                case 'chong':
                case 'cha':
                case 'me':
                case 'anh_chi_em':
                case 'khac':
                    if ($dependent->is_disabled) {
                        $count++;
                    }
                    break;
            }
        }
        return $count;
    }

    /**
     * Định nghĩa mối quan hệ với bảng 'income_declarations'.
     * Một người dùng có nhiều khai báo thu nhập.
     */
    public function incomeDeclarations(): HasMany
    {
        return $this->hasMany(IncomeDeclaration::class);
    }
}