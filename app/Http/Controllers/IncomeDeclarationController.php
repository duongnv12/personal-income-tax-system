<?php

namespace App\Http\Controllers;

use App\Models\IncomeDeclaration;
use App\Services\TaxCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import facade PDF cho việc xuất PDF

class IncomeDeclarationController extends Controller
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
     * Hiển thị danh sách các khai báo thu nhập của người dùng hiện tại.
     * Sắp xếp theo tháng khai báo giảm dần.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Lấy người dùng hiện tại đã đăng nhập
        $user = Auth::user();

        // Lấy tất cả các khai báo thu nhập của người dùng này, sắp xếp theo tháng giảm dần
        $declarations = $user->incomeDeclarations()->orderBy('declaration_month', 'desc')->get();

        // Trả về view 'income_declarations.index' và truyền dữ liệu khai báo
        return view('income_declarations.index', compact('declarations'));
    }

    /**
     * Hiển thị form để thêm khai báo thu nhập mới.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Trả về view 'income_declarations.create' để hiển thị form thêm mới
        return view('income_declarations.create');
    }

    /**
     * Lưu khai báo thu nhập mới vào database và tự động tính toán thuế TNCN.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Validate dữ liệu đầu vào từ form
        $validatedData = $request->validate([
            'declaration_month' => [
                'required',
                'date_format:Y-m', // Đảm bảo định dạng là YYYY-MM
                // Đảm bảo mỗi người dùng chỉ có một khai báo cho một tháng cụ thể
                Rule::unique('income_declarations')->where(function ($query) use ($user, $request) {
                    $month = Carbon::parse($request->declaration_month)->startOfMonth();
                    return $query->where('user_id', $user->id)
                                 ->where('declaration_month', $month);
                }),
                'before_or_equal:' . Carbon::now()->format('Y-m'), // Không cho phép khai báo tháng trong tương lai
            ],
            'gross_salary' => 'required|integer|min:0', // Lương Gross là số nguyên không âm
            'other_taxable_income' => 'nullable|integer|min:0', // Thu nhập chịu thuế khác, có thể rỗng
            'non_taxable_income' => 'nullable|integer|min:0', // Thu nhập miễn thuế, có thể rỗng
            'deduction_charity' => 'nullable|integer|min:0', // Giảm trừ từ thiện, có thể rỗng
            'tax_deducted_at_source' => 'nullable|integer|min:0', // Thuế đã khấu trừ tại nguồn, có thể rỗng
        ]);

        // Gán giá trị mặc định 0 nếu các trường nullable không được gửi
        $grossSalary = (int) $validatedData['gross_salary'];
        $otherTaxableIncome = (int) ($validatedData['other_taxable_income'] ?? 0);
        $nonTaxableIncome = (int) ($validatedData['non_taxable_income'] ?? 0);
        $deductionCharity = (int) ($validatedData['deduction_charity'] ?? 0);
        $taxDeductedAtSource = (int) ($validatedData['tax_deducted_at_source'] ?? 0);
        $declarationMonth = Carbon::parse($validatedData['declaration_month'])->startOfMonth();

        // 2. Gọi TaxCalculationService để tính toán thuế và các khoản liên quan
        // Truyền user object để service có thể lấy thông tin người phụ thuộc
        $calculationResults = $this->taxCalculationService->calculateMonthlyPIT(
            $user,
            $grossSalary,
            $otherTaxableIncome,
            $nonTaxableIncome,
            $deductionCharity,
            $declarationMonth
        );

        // 3. Tạo một bản ghi IncomeDeclaration mới và lưu vào database
        $user->incomeDeclarations()->create([
            'declaration_month' => $declarationMonth,
            'gross_salary' => $grossSalary,
            'other_taxable_income' => $otherTaxableIncome,
            'non_taxable_income' => $nonTaxableIncome,
            'deduction_charity' => $deductionCharity,
            'tax_deducted_at_source' => $taxDeductedAtSource,
            'social_insurance_contribution' => $calculationResults['social_insurance_contribution'], // Lấy từ kết quả tính toán
            'personal_deduction' => $calculationResults['personal_deduction'], // Lấy từ kết quả tính toán
            'dependent_deduction' => $calculationResults['dependent_deduction'], // Lấy từ kết quả tính toán
            'total_deduction' => $calculationResults['total_deduction'], // Lấy từ kết quả tính toán
            'taxable_income' => $calculationResults['taxable_income'], // Lấy từ kết quả tính toán
            'calculated_tax' => $calculationResults['pit_amount'], // Lấy từ kết quả tính toán
            'net_salary' => $calculationResults['net_salary'], // Lấy từ kết quả tính toán
        ]);

        // Chuyển hướng về trang danh sách khai báo với thông báo thành công
        return redirect()->route('income_declarations.index')->with('success', 'Đã thêm khai báo thu nhập thành công!');
    }

    /**
     * Hiển thị chi tiết một khai báo thu nhập cụ thể với giải thích các bước tính toán.
     *
     * @param \App\Models\IncomeDeclaration $incomeDeclaration
     * @return \Illuminate\View\View
     */
    public function show(IncomeDeclaration $incomeDeclaration)
    {
        // Đảm bảo người dùng hiện tại có quyền xem khai báo này
        if ($incomeDeclaration->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập khai báo này.');
        }

        // Gọi TaxCalculationService để tính toán lại chi tiết và lấy các bước giải thích
        // Tham số cuối cùng là 'true' để yêu cầu chi tiết các bước
        $detailedCalculation = $this->taxCalculationService->calculateMonthlyPIT(
            $incomeDeclaration->user,
            $incomeDeclaration->gross_salary,
            $incomeDeclaration->other_taxable_income,
            $incomeDeclaration->non_taxable_income,
            $incomeDeclaration->deduction_charity,
            $incomeDeclaration->declaration_month, // Truyền đối tượng Carbon cho tháng
            true // Yêu cầu trả về chi tiết các bước
        );

        // Trả về view 'income_declarations.show' và truyền dữ liệu khai báo cùng chi tiết tính toán
        return view('income_declarations.show', compact('incomeDeclaration', 'detailedCalculation'));
    }

    /**
     * Hiển thị form để chỉnh sửa thông tin khai báo thu nhập.
     *
     * @param \App\Models\IncomeDeclaration $incomeDeclaration
     * @return \Illuminate\View\View
     */
    public function edit(IncomeDeclaration $incomeDeclaration)
    {
        // Đảm bảo người dùng hiện tại có quyền chỉnh sửa khai báo này
        if ($incomeDeclaration->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa khai báo này.');
        }

        // Trả về view 'income_declarations.edit' và truyền dữ liệu khai báo
        return view('income_declarations.edit', compact('incomeDeclaration'));
    }

    /**
     * Cập nhật thông tin khai báo thu nhập trong database và tính toán lại thuế.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\IncomeDeclaration $incomeDeclaration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, IncomeDeclaration $incomeDeclaration)
    {
        // Đảm bảo người dùng hiện tại có quyền cập nhật khai báo này
        if ($incomeDeclaration->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền cập nhật khai báo này.');
        }

        $user = Auth::user();

        // 1. Validate dữ liệu đầu vào từ form
        $validatedData = $request->validate([
            'declaration_month' => [
                'required',
                'date_format:Y-m',
                // Đảm bảo tháng khai báo là duy nhất cho người dùng này, bỏ qua chính bản ghi đang cập nhật
                Rule::unique('income_declarations')->where(function ($query) use ($user, $request) {
                    $month = Carbon::parse($request->declaration_month)->startOfMonth();
                    return $query->where('user_id', $user->id)
                                 ->where('declaration_month', $month);
                })->ignore($incomeDeclaration->id),
                'before_or_equal:' . Carbon::now()->format('Y-m'),
            ],
            'gross_salary' => 'required|integer|min:0',
            'other_taxable_income' => 'nullable|integer|min:0',
            'non_taxable_income' => 'nullable|integer|min:0',
            'deduction_charity' => 'nullable|integer|min:0',
            'tax_deducted_at_source' => 'nullable|integer|min:0',
        ]);

        // Gán giá trị mặc định 0 nếu các trường nullable không được gửi
        $grossSalary = (int) $validatedData['gross_salary'];
        $otherTaxableIncome = (int) ($validatedData['other_taxable_income'] ?? 0);
        $nonTaxableIncome = (int) ($validatedData['non_taxable_income'] ?? 0);
        $deductionCharity = (int) ($validatedData['deduction_charity'] ?? 0);
        $taxDeductedAtSource = (int) ($validatedData['tax_deducted_at_source'] ?? 0);
        $declarationMonth = Carbon::parse($validatedData['declaration_month'])->startOfMonth();

        // 2. Gọi TaxCalculationService để tính toán lại thuế và các khoản liên quan
        $calculationResults = $this->taxCalculationService->calculateMonthlyPIT(
            $user,
            $grossSalary,
            $otherTaxableIncome,
            $nonTaxableIncome,
            $deductionCharity,
            $declarationMonth
        );

        // 3. Cập nhật bản ghi IncomeDeclaration trong database
        $incomeDeclaration->update([
            'declaration_month' => $declarationMonth,
            'gross_salary' => $grossSalary,
            'other_taxable_income' => $otherTaxableIncome,
            'non_taxable_income' => $nonTaxableIncome,
            'deduction_charity' => $deductionCharity,
            'tax_deducted_at_source' => $taxDeductedAtSource,
            'social_insurance_contribution' => $calculationResults['social_insurance_contribution'],
            'personal_deduction' => $calculationResults['personal_deduction'],
            'dependent_deduction' => $calculationResults['dependent_deduction'],
            'total_deduction' => $calculationResults['total_deduction'],
            'taxable_income' => $calculationResults['taxable_income'],
            'calculated_tax' => $calculationResults['pit_amount'],
            'net_salary' => $calculationResults['net_salary'],
        ]);

        // Chuyển hướng về trang danh sách khai báo với thông báo thành công
        return redirect()->route('income_declarations.index')->with('success', 'Đã cập nhật khai báo thu nhập thành công!');
    }

    /**
     * Xóa khai báo thu nhập khỏi database.
     *
     * @param \App\Models\IncomeDeclaration $incomeDeclaration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(IncomeDeclaration $incomeDeclaration)
    {
        // Đảm bảo người dùng hiện tại có quyền xóa khai báo này
        if ($incomeDeclaration->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa khai báo này.');
        }

        // Xóa bản ghi khai báo
        $incomeDeclaration->delete();

        // Chuyển hướng về trang danh sách khai báo với thông báo thành công
        return redirect()->route('income_declarations.index')->with('success', 'Đã xóa khai báo thu nhập thành công!');
    }

    /**
     * Xuất khai báo thu nhập cụ thể ra file PDF.
     *
     * @param \App\Models\IncomeDeclaration $incomeDeclaration
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(IncomeDeclaration $incomeDeclaration)
    {
        // Đảm bảo người dùng hiện tại có quyền xuất PDF khai báo của chính họ
        if ($incomeDeclaration->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập khai báo này.');
        }

        // Gọi TaxCalculationService để tính toán lại chi tiết và lấy các bước giải thích cho PDF
        $detailedCalculation = $this->taxCalculationService->calculateMonthlyPIT(
            $incomeDeclaration->user,
            $incomeDeclaration->gross_salary,
            $incomeDeclaration->other_taxable_income,
            $incomeDeclaration->non_taxable_income,
            $incomeDeclaration->deduction_charity,
            $incomeDeclaration->declaration_month,
            true // Yêu cầu trả về chi tiết các bước
        );

        // Render view 'pdf.income_declaration' thành HTML để tạo PDF
        $pdf = Pdf::loadView('pdf.income_declaration', compact('incomeDeclaration', 'detailedCalculation'));

        // Đặt tên file PDF khi tải xuống
        $filename = 'Khai_bao_thu_nhap_thang_' . $incomeDeclaration->declaration_month->format('Y_m') . '_' . $incomeDeclaration->user->name . '.pdf';

        // Tải xuống file PDF
        return $pdf->download($filename);
    }
}
