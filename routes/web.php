<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\DependentController;
use App\Http\Controllers\IncomeDeclarationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SystemConfigController;
use App\Http\Controllers\Admin\TaxBracketController;
use App\Http\Controllers\Admin\UserController;
use App\Services\TaxCalculationService;
use App\Models\IncomeDeclaration;
use App\Models\SystemConfig; // <-- Thêm dòng này để sử dụng SystemConfig
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Route Dashboard cho người dùng đã đăng nhập
    Route::get('/dashboard', function (Request $request, TaxCalculationService $taxCalculationService) {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;
        $currentMonthDate = Carbon::now()->startOfMonth(); // Dùng để lấy config theo tháng hiện tại

        // Lấy cấu hình giảm trừ người phụ thuộc hiện hành
        $dependentDeductionAmountPerMonth = SystemConfig::where('key', 'dependent_deduction_amount')
                                                        ->where('effective_date', '<=', $currentMonthDate)
                                                        ->orderBy('effective_date', 'desc')
                                                        ->first()
                                                        ->value ?? 4400000; // Giá trị mặc định nếu không tìm thấy

        // Lấy tất cả các khai báo thu nhập trong năm hiện tại của người dùng
        $declarations = $user->incomeDeclarations()
                             ->whereYear('declaration_month', $currentYear)
                             ->orderBy('declaration_month', 'asc')
                             ->get();

        // Lấy khai báo thu nhập gần nhất
        $latestMonthlyDeclaration = $user->incomeDeclarations()
                                         ->orderBy('declaration_month', 'desc')
                                         ->first();

        $annualSummary = null;
        if ($declarations->isNotEmpty()) {
            $annualSummary = $taxCalculationService->calculateAnnualPIT($user, $currentYear);
        } else {
            // Nếu không có khai báo nào, cung cấp giá trị mặc định cho annualSummary
            // Đảm bảo tất cả các khóa mà dashboard sử dụng đều có mặt
            $annualSummary = [
                'year' => $currentYear,
                'annual_gross_salary' => 0,
                'annual_other_taxable_income' => 0,
                'annual_non_taxable_income' => 0,
                'annual_deduction_charity' => 0,
                'annual_total_taxable_income' => 0,
                'annual_social_insurance_contribution' => 0,
                'annual_personal_deduction' => 0,
                'annual_dependent_deduction' => 0,
                'annual_total_deduction' => 0,
                'annual_taxable_income' => 0,
                'annual_pit_amount' => 0,
                'annual_tax_deducted_at_source' => 0,
                'tax_to_pay_or_refund' => 0,
                'status' => 'Không có dữ liệu khai báo',
                'monthly_summaries' => [],
                // Kể cả nếu bạn dùng chi tiết bước, hãy thêm các khóa đó vào đây nếu không có
                // 'steps' => [], // Chỉ thêm nếu bạn muốn hiển thị bước ngay cả khi không có dữ liệu
            ];
        }

        return view('dashboard', compact('declarations', 'annualSummary', 'currentYear', 'latestMonthlyDeclaration', 'dependentDeductionAmountPerMonth'));
    })->middleware(['verified'])->name('dashboard');

    // ... (Giữ nguyên các routes profile, dependents, income_declarations, tax, admin) ...
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('dependents', DependentController::class);

    Route::resource('income_declarations', IncomeDeclarationController::class);
    Route::get('/income_declarations/{income_declaration}/pdf', [IncomeDeclarationController::class, 'exportPdf'])->name('income_declarations.export_pdf');

    Route::get('/tax/annual-settlement', [TaxController::class, 'annualSettlement'])->name('tax.annual_settlement');
    Route::get('/tax/annual-settlement/pdf', [TaxController::class, 'exportAnnualSettlementPdf'])->name('tax.export_annual_settlement_pdf');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
        Route::resource('system_configs', SystemConfigController::class)->except(['show']);
        Route::post('/system_configs/add-new', [SystemConfigController::class, 'storeQuick'])->name('system_configs.store_quick');
        Route::resource('tax_brackets', TaxBracketController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';