<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Đảm bảo đã import Carbon

class Dependent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'dob',
        'relationship',
        'is_disabled',
        'identification_number',
        'registration_date',
        'cancellation_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dob' => 'date', // Cast ngày sinh sang Carbon instance
        'is_disabled' => 'boolean', // Cast boolean
        'registration_date' => 'date', // Cast ngày đăng ký sang Carbon instance
        'cancellation_date' => 'date', // Cast ngày hủy đăng ký sang Carbon instance
    ];

    /**
     * Định nghĩa mối quan hệ ngược với bảng 'users'.
     * Một người phụ thuộc thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kiểm tra xem người phụ thuộc có hợp lệ để được giảm trừ trong một tháng cụ thể hay không.
     *
     * @param Carbon $checkDate Ngày kiểm tra (thường là ngày cuối cùng của tháng)
     * @return bool True nếu hợp lệ, False nếu không
     */
    public function isValidForDeduction(Carbon $checkDate): bool
    {
        // 1. Kiểm tra ngày đăng ký: Phải đăng ký trước hoặc trong tháng kiểm tra
        if ($this->registration_date && $this->registration_date->greaterThan($checkDate)) {
            return false;
        }

        // 2. Kiểm tra ngày hủy đăng ký: Nếu có ngày hủy, phải hủy sau tháng kiểm tra
        if ($this->cancellation_date && $this->cancellation_date->lessThanOrEqualTo($checkDate)) {
            return false;
        }

        // 3. Kiểm tra điều kiện theo mối quan hệ và tình trạng
        switch ($this->relationship) {
            case 'con':
                // Con dưới 18 tuổi vào cuối tháng kiểm tra
                if ($this->dob) { // Đảm bảo có ngày sinh
                    $ageAtCheckDate = $this->dob->diffInYears($checkDate);
                    return $ageAtCheckDate < 18;
                }
                return false; // Nếu không có ngày sinh, không hợp lệ
            case 'vo':
            case 'chong':
            case 'cha':
            case 'me':
            case 'anh_chi_em':
            case 'khac':
                // Các đối tượng khác hợp lệ nếu bị khuyết tật
                return $this->is_disabled;
            default:
                return false; // Mối quan hệ không xác định
        }
    }
}