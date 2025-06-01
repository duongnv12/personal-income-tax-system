<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\IncomeDeclaration;
use App\Models\Dependent;
use App\Models\SystemConfig;
use App\Models\TaxBracket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Lấy các số liệu thống kê cho admin dashboard
        $totalUsers = User::count();
        $totalIncomeDeclarations = IncomeDeclaration::count();
        $totalDependents = Dependent::count();
        $totalSystemConfigs = SystemConfig::count();
        $totalTaxBrackets = TaxBracket::count();

        // Có thể thêm các số liệu khác nếu cần (ví dụ: số người dùng mới trong tháng, v.v.)

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalIncomeDeclarations',
            'totalDependents',
            'totalSystemConfigs',
            'totalTaxBrackets'
        ));
    }
}