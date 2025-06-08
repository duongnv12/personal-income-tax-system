<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IncomeEntry;
use App\Models\Dependent;
use App\Services\TaxCalculationService;

class UserDashboardController extends Controller
{
    protected $taxService;

    public function __construct(TaxCalculationService $taxService)
    {
        $this->taxService = $taxService;
    }

    /**
     * Display the user's personal dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Lấy tất cả các khoản thu nhập của người dùng (không cần xử lý lại net/tax/bhxh ở đây nữa)
        $incomeEntries = $user->incomeEntries()->with('incomeSource')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        // Lấy tất cả người phụ thuộc của người dùng
        $dependents = $user->dependents()->get();

        // Tính toán quyết toán thuế cho năm hiện tại
        $currentYear = date('Y');
        $yearlyTaxSettlement = $this->taxService->calculateYearlyTaxSettlement($user, $currentYear);

        return view('dashboard', [
            'user' => $user,
            'incomeEntries' => $incomeEntries,
            'dependents' => $dependents,
            'currentYear' => $currentYear,
            'yearlyTaxSettlement' => $yearlyTaxSettlement,
        ]);
    }
}