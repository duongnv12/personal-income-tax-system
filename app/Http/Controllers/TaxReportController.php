<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TaxCalculationService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import facade PDF
use Maatwebsite\Excel\Facades\Excel; // Import facade Excel
use App\Exports\YearlyTaxSettlementExport; // Import Export Class
use App\Models\IncomeSource;
use Illuminate\Support\Facades\Log; // Import Log facade

class TaxReportController extends Controller
{
    protected $taxService;

    public function __construct(TaxCalculationService $taxService)
    {
        $this->taxService = $taxService;
    }

    /**
     * Display the yearly tax settlement report.
     *
     * @param int|null $year
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showYearlySettlement(?int $year = null)
    {
        $user = Auth::user();
        $currentYear = (int)date('Y');

        if ($year !== null && ($year < 1900 || $year > ($currentYear + 1))) {
            return redirect()->route('tax.yearly_settlement', $currentYear)
                             ->with('error', 'Năm không hợp lệ.');
        }

        $selectedYear = $year ?? $currentYear;

        try {
            // Tính toán quyết toán tổng hợp (giữ nguyên)
            $yearlyTaxSettlement = $this->taxService->calculateYearlyTaxSettlement($user, $selectedYear);

            // THÊM MỚI: Lấy dữ liệu chi tiết theo từng nguồn
            $breakdownBySource = $this->taxService->getYearlyBreakdownBySource($user, $selectedYear);

        } catch (\Exception $e) {
            Log::error("Lỗi khi tính toán quyết toán thuế cho người dùng ID {$user->id} năm {$selectedYear}: " . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Có lỗi xảy ra khi lấy dữ liệu quyết toán thuế.');
        }

        $availableYears = $user->incomeEntries()
                               ->selectRaw('DISTINCT year')
                               ->orderBy('year', 'desc')
                               ->pluck('year')
                               ->toArray();
        if (!in_array($currentYear, $availableYears)) {
            array_unshift($availableYears, $currentYear);
            sort($availableYears);
        }
        if (!in_array($selectedYear, $availableYears)) {
            array_unshift($availableYears, $selectedYear);
            sort($availableYears);
        }

        // Lấy tổng thu nhập, tổng thuế đã tạm nộp, tổng thuế phải nộp từng năm để vẽ biểu đồ
        $incomeEntriesByYear = $user->incomeEntries()
            ->selectRaw('year, SUM(gross_income) as total_income, SUM(tax_paid) as total_tax_paid')
            ->groupBy('year')
            ->orderBy('year')
            ->get();
        $incomeByYear = [];
        $taxPaidByYear = [];
        $taxRequiredByYear = [];
        foreach ($incomeEntriesByYear as $row) {
            $incomeByYear[$row->year] = (float) $row->total_income;
            $taxPaidByYear[$row->year] = (float) $row->total_tax_paid;
        }
        // Lấy tổng thuế phải nộp từng năm từ service (nếu có)
        foreach (array_keys($incomeByYear) as $year) {
            $settlement = $this->taxService->calculateYearlyTaxSettlement($user, $year);
            $taxRequiredByYear[$year] = isset($settlement['total_tax_required_yearly']) ? (float) $settlement['total_tax_required_yearly'] : 0;
        }

        return view('tax-reports.yearly-settlement', [
            'yearlyTaxSettlement' => $yearlyTaxSettlement,
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
            'breakdownBySource' => $breakdownBySource, // Gửi dữ liệu mới sang view
            'incomeByYear' => $incomeByYear,
            'taxPaidByYear' => $taxPaidByYear,
            'taxRequiredByYear' => $taxRequiredByYear,
        ]);
    }

    /**
     * Export the yearly tax settlement report to PDF.
     *
     * @param int $year
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function exportYearlySettlementPdf(int $year)
    {
        $user = Auth::user();
        $currentYear = (int)date('Y');

        // Validation cho năm
        if ($year < 1900 || $year > ($currentYear + 1)) {
            return back()->with('error', 'Năm không hợp lệ để xuất PDF. Vui lòng chọn năm từ 1900 đến ' . ($currentYear + 1) . '.');
        }

        try {
            // Tính toán quyết toán thuế cho năm được chọn
            $yearlyTaxSettlement = $this->taxService->calculateYearlyTaxSettlement($user, $year);

            // Lấy chi tiết các khoản thu nhập trong năm
            $incomeEntriesForSelectedYear = $user->incomeEntries()
                                                 ->where('year', $year)
                                                 ->with('incomeSource')
                                                 ->orderBy('month')
                                                 ->get();

            // Chuẩn bị dữ liệu cho view PDF
            $data = [
                'user' => $user,
                'yearlyTaxSettlement' => $yearlyTaxSettlement,
                'selectedYear' => $year,
                'incomeEntriesForSelectedYear' => $incomeEntriesForSelectedYear,
                'currentDate' => Carbon::now()->format('d/m/Y H:i'),
            ];

            // Load view vào PDF
            $pdf = Pdf::loadView('tax-reports.yearly-settlement-pdf', $data);

            // Tùy chỉnh tên file
            $filename = 'Quyet_toan_thue_TNCN_' . $user->id . '_' . $year . '.pdf';

            // Tải về file PDF
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Lỗi khi xuất PDF quyết toán thuế cho người dùng ID {$user->id} năm {$year}: " . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xuất báo cáo PDF. Vui lòng thử lại.');
        }
    }

    /**
     * Export the yearly tax settlement report to Excel.
     *
     * @param int $year
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function exportYearlySettlementExcel(int $year)
    {
        $user = Auth::user();
        $currentYear = (int)date('Y');

        // Validation cho năm
        if ($year < 1900 || $year > ($currentYear + 1)) {
            return back()->with('error', 'Năm không hợp lệ để xuất Excel. Vui lòng chọn năm từ 1900 đến ' . ($currentYear + 1) . '.');
        }

        try {
            $filename = 'Quyet_toan_thue_TNCN_' . $user->id . '_' . $year . '.xlsx';

            // Sử dụng Export Class để tải về Excel
            return Excel::download(new YearlyTaxSettlementExport($user, $year, $this->taxService), $filename);
        } catch (\Exception $e) {
            Log::error("Lỗi khi xuất Excel quyết toán thuế cho người dùng ID {$user->id} năm {$year}: " . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xuất báo cáo Excel. Vui lòng thử lại.');
        }
    }

    /**
     * Export yearly taxable income report by company to PDF.
     *
     * @param int $year
     * @param int $companyId
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function exportYearlyCompanyIncomePdf(int $year, int $companyId)
    {
        $user = Auth::user();
        $currentYear = (int)date('Y');

        // Validation cho năm
        if ($year < 1900 || $year > ($currentYear + 1)) {
            return back()->with('error', 'Năm không hợp lệ để xuất PDF.');
        }

        try {
            // Lấy thu nhập theo công ty và năm
            $incomeEntries = $user->incomeEntries()
                ->where('year', $year)
                ->where('income_source_id', $companyId)
                ->with('incomeSource')
                ->orderBy('month')
                ->get();

            if ($incomeEntries->isEmpty()) {
                return back()->with('error', 'Không có dữ liệu thu nhập cho công ty này trong năm đã chọn.');
            }

            // Tính toán tổng thu nhập, thuế... (tuỳ logic)
            $taxableSummary = $this->taxService->calculateCompanyYearlyTax($user, $year, $companyId);

            $data = [
                'user' => $user,
                'incomeEntries' => $incomeEntries,
                'taxableSummary' => $taxableSummary,
                'selectedYear' => $year,
                'company' => $incomeEntries->first()->incomeSource,
                'currentDate' => now()->format('d/m/Y H:i'),
            ];

            $pdf = Pdf::loadView('tax-reports.company-yearly-income-pdf', $data);
            $filename = 'Thu_nhap_tinh_thue_' . $user->id . '_' . $year . '_cty_' . $companyId . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Lỗi khi xuất PDF thu nhập công ty cho user {$user->id} năm {$year} cty {$companyId}: " . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xuất PDF.');
        }
    }

    /**
     * Lấy chi tiết các khoản thu nhập của một nguồn cụ thể dưới dạng JSON.
     */
    public function getSourceDetailsJson(Request $request, int $year, IncomeSource $source)
    {
        // Đảm bảo người dùng chỉ có thể truy cập dữ liệu của chính họ
        if ($source->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Lấy tất cả các khoản thu nhập từ nguồn này trong năm
        $entries = $source->incomeEntries()
            ->where('year', $year)
            ->orderBy('month', 'asc')
            ->get();

        // Tạo một bản tóm tắt
        $summary = [
            'source_name' => $source->name,
            'total_gross' => $entries->sum('gross_income'),
            'total_bhxh' => $entries->sum('bhxh_deduction'),
            'total_tax_paid' => $entries->sum('tax_paid'),
        ];
        
        // Trả về dữ liệu dưới dạng JSON
        return response()->json([
            'summary' => $summary,
            'entries' => $entries,
        ]);
    }

    /**
     * API: Chi tiết nguồn thu nhập cho modal popup báo cáo thuế năm
     */
    public function sourceDetails(Request $request, $year, $sourceId)
    {
        $user = Auth::user();
        $source = \App\Models\IncomeSource::findOrFail($sourceId);
        if ($source->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $entries = $source->incomeEntries()
            ->where('year', $year)
            ->orderBy('month', 'asc')
            ->get();
        $summary = [
            'source_name' => $source->name,
            'total_gross' => $entries->sum('gross_income'),
            'total_bhxh' => $entries->sum('bhxh_deduction'),
            'total_other_deductions' => $entries->sum('other_deductions'),
            'total_personal_deductions' => $entries->sum('personal_deductions'), // Nếu có
            'total_tax_paid' => $entries->sum('tax_paid'),
        ];
        return response()->json([
            'summary' => $summary,
            'entries' => $entries,
        ]);
    }
}