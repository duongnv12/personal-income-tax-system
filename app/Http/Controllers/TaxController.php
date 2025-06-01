<?php

namespace App\Http\Controllers;

use App\Services\TaxCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import facade PDF cho việc xuất PDF

class TaxController extends Controller
{
    /**
     * Thuộc tính để lưu trữ instance của TaxCalculationService.
     *
     * @var TaxCalculationService
     */
    protected $taxCalculationService;

    /**
     * Constructor của Controller.
     * Inject TaxCalculationService vào Controller.
     *
     * @param TaxCalculationService $taxCalculationService
     */
    public function __construct(TaxCalculationService $taxCalculationService)
    {
        $this->taxCalculationService = $taxCalculationService;
    }

    /**
     * Hiển thị trang quyết toán thuế cuối năm.
     * Trang này sẽ tổng hợp dữ liệu từ các khai báo thu nhập hàng tháng
     * và tính toán số thuế TNCN phải nộp/hoàn lại cho cả năm.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function annualSettlement(Request $request)
    {
        $user = Auth::user(); // Lấy người dùng hiện tại
        $currentYear = Carbon::now()->year; // Lấy năm hiện tại

        // Lấy năm từ request nếu có, hoặc mặc định là năm hiện tại
        $year = $request->input('year', $currentYear);

        // Gọi TaxCalculationService để tính toán quyết toán năm
        // Tham số cuối cùng là 'true' để yêu cầu chi tiết các bước tính toán
        $settlementResults = $this->taxCalculationService->calculateAnnualPIT($user, $year, true);

        // Trả về view 'tax.annual_settlement' và truyền kết quả cùng năm đã chọn
        return view('tax.annual_settlement', compact('settlementResults', 'year'));
    }

    /**
     * Xuất báo cáo quyết toán thuế cuối năm ra file PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportAnnualSettlementPdf(Request $request)
    {
        $user = Auth::user(); // Lấy người dùng hiện tại
        $currentYear = Carbon::now()->year; // Lấy năm hiện tại

        // Lấy năm từ request nếu có, hoặc mặc định là năm hiện tại
        $year = $request->input('year', $currentYear);

        // Gọi TaxCalculationService để tính toán quyết toán năm và lấy chi tiết các bước
        $settlementResults = $this->taxCalculationService->calculateAnnualPIT($user, $year, true);

        // Render view 'pdf.annual_settlement' thành HTML để tạo PDF
        $pdf = Pdf::loadView('pdf.annual_settlement', compact('settlementResults', 'year'));

        // Đặt tên file PDF khi tải xuống
        $filename = 'Quyet_toan_thue_TNCN_nam_' . $year . '_' . $user->name . '.pdf';

        // Tải xuống file PDF
        return $pdf->download($filename);
    }
}
