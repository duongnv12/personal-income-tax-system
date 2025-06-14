<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TaxCalculationService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import facade PDF
use Maatwebsite\Excel\Facades\Excel; // Import facade Excel
use App\Exports\YearlyTaxSettlementExport; // Import Export Class
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
        $currentYear = (int)date('Y'); // Chắc chắn là số nguyên

        // Validation cơ bản cho năm
        if ($year !== null && ($year < 1900 || $year > ($currentYear + 1))) {
            return redirect()->route('tax-reports.yearly-settlement', ['year' => $currentYear])
                             ->with('error', 'Năm không hợp lệ. Vui lòng chọn năm từ 1900 đến ' . ($currentYear + 1) . '.');
        }

        $selectedYear = $year ?? $currentYear;

        try {
            // Tính toán quyết toán thuế cho năm được chọn
            $yearlyTaxSettlement = $this->taxService->calculateYearlyTaxSettlement($user, $selectedYear);
        } catch (\Exception $e) {
            Log::error("Lỗi khi tính toán quyết toán thuế cho người dùng ID {$user->id} năm {$selectedYear}: " . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Có lỗi xảy ra khi lấy dữ liệu quyết toán thuế. Vui lòng thử lại sau.');
        }

        // Lấy danh sách các năm mà người dùng có khoản thu nhập để hiển thị dropdown lựa chọn
        $availableYears = $user->incomeEntries()
                               ->selectRaw('DISTINCT year')
                               ->orderBy('year', 'desc')
                               ->pluck('year')
                               ->toArray();

        // Đảm bảo năm hiện tại/năm được chọn có trong danh sách nếu chưa có thu nhập
        if (!in_array($currentYear, $availableYears)) {
            array_unshift($availableYears, $currentYear);
            sort($availableYears); // Sắp xếp lại nếu thêm năm hiện tại
        }

        // Thêm năm đã chọn vào danh sách nếu nó chưa có (trường hợp người dùng chọn năm tương lai hợp lệ)
        if (!in_array($selectedYear, $availableYears)) {
            array_unshift($availableYears, $selectedYear);
            sort($availableYears);
        }

        return view('tax-reports.yearly-settlement', [
            'yearlyTaxSettlement' => $yearlyTaxSettlement,
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
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
}