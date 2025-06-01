<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Đảm bảo đã import User
use App\Models\SystemConfig; // Đảm bảo đã import SystemConfig
use Carbon\Carbon; // Đảm bảo đã import Carbon

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalDependents = $user->dependents()->count();

        // Lấy config cho tính toán nhanh trên dashboard (có thể tùy chỉnh)
        $personalDeduction = SystemConfig::getEffectiveValue('personal_deduction_amount', Carbon::now());
        $dependentDeduction = SystemConfig::getEffectiveValue('dependent_deduction_amount', Carbon::now());

        // Đây là ví dụ, bạn có thể thêm logic để lấy tổng thu nhập năm, thuế đã nộp...
        // Ví dụ: lấy số người phụ thuộc hợp lệ trong tháng hiện tại
        $validDependentsThisMonth = $user->getValidDependentsCount(Carbon::now()->year, Carbon::now()->month);


        return view('dashboard', compact(
            'user',
            'totalDependents',
            'personalDeduction',
            'dependentDeduction',
            'validDependentsThisMonth'
        ));
    }
}