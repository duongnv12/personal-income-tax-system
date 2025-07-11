<?php

namespace App\Services;

use App\Models\User;
use App\Models\IncomeEntry;
use App\Models\TaxParameter;
use App\Models\TaxBracket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 
class TaxCalculationService
{
    protected $taxParameters;
    protected $taxBrackets;

    // Định nghĩa các hằng số để dễ dàng tham chiếu các tham số thuế
    const PERSONAL_DEDUCTION_KEY = 'PERSONAL_DEDUCTION';
    const DEPENDENT_DEDUCTION_KEY = 'DEPENDENT_DEDUCTION';
    const SOCIAL_INSURANCE_RATE_KEY = 'SOCIAL_INSURANCE_RATE'; // Tỷ lệ BHXH, BHYT, BHTN của NLĐ
    const MAX_SOCIAL_INSURANCE_KEY = 'MAX_SOCIAL_INSURANCE'; // Trần lương tính BHXH, BHYT, BHTN

    public function __construct()
    {
        $this->loadTaxParameters();
        $this->loadTaxBrackets();
    }

    /**
     * Tải các tham số thuế từ database và cung cấp giá trị mặc định.
     */
    protected function loadTaxParameters()
    {
        $params = TaxParameter::pluck('param_value', 'param_key')->toArray();
        // Gán các giá trị mặc định để tránh lỗi nếu database trống hoặc thiếu key
        $this->taxParameters = array_merge([
            self::PERSONAL_DEDUCTION_KEY => 11000000,
            self::DEPENDENT_DEDUCTION_KEY => 4400000,
            self::SOCIAL_INSURANCE_RATE_KEY => 0.105, // 8% BHXH + 1.5% BHYT + 1% BHTN = 10.5% (tổng các khoản người lao động đóng)
            self::MAX_SOCIAL_INSURANCE_KEY => 29800000, // Trần lương đóng BHXH
        ], $params);
    }

    /**
     * Tải các bậc thuế từ database.
     */
    protected function loadTaxBrackets()
    {
        $this->taxBrackets = TaxBracket::orderBy('level')->get()->toArray();
    }

    public function getTaxBrackets()
    {
        return $this->taxBrackets;
    }

    /**
     * Tính toán thuế lũy tiến cho thu nhập từ tiền lương, tiền công.
     * Logic này được thiết kế để tính thuế dựa trên các bậc thuế đã cho.
     *
     * @param float $taxableIncome Thu nhập tính thuế
     * @return float
     */
    private function calculateProgressiveTaxSalary(float $taxableIncome): float
    {
        $totalTax = 0;

        foreach ($this->taxBrackets as $bracket) {
            $incomeFrom = $bracket['income_from'];
            $incomeTo = $bracket['income_to'];
            $taxRate = $bracket['tax_rate'];

            // Nếu thu nhập tính thuế vượt qua ngưỡng dưới của bậc hiện tại
            if ($taxableIncome > $incomeFrom) {
                $amountInBracket = 0;

                if ($incomeTo === null) { // Bậc cuối cùng (không giới hạn trên)
                    $amountInBracket = $taxableIncome - $incomeFrom;
                } else {
                    // Lượng thu nhập trong bậc hiện tại (chưa vượt qua giới hạn trên của bậc)
                    $amountInBracket = min($taxableIncome, $incomeTo) - $incomeFrom;
                }

                $totalTax += $amountInBracket * $taxRate;
            }
        }
        return round($totalTax, 0);
    }


    /**
     * Calculates tax for business income.
     * (Currently fixed rate 1.5% for presumptive tax method based on revenue for individuals)
     * This is a simplified example. Actual business tax can be complex.
     *
     * @param float $revenue
     * @return float
     */
    private function calculateTaxBusiness(float $revenue): float
    {
        // Giả định: Thuế TNCN từ kinh doanh áp dụng phương pháp khoán đối với cá nhân
        // Doanh thu trên 100 triệu/năm mới phải nộp thuế.
        // Thuế suất TNCN: 0.5%
        // Để đơn giản, giả sử chỉ tính TNCN 0.5% trên doanh thu vượt 100 triệu.
        $threshold = 100000000; // 100 triệu VNĐ/năm
        if ($revenue <= $threshold) {
            return 0;
        }
        $taxRate = 0.005; // 0.5% trên doanh thu (chỉ riêng TNCN)
        return round(($revenue - $threshold) * $taxRate, 0); // Thuế tính trên phần vượt ngưỡng
    }

    /**
     * Calculates tax for investment income.
     * (Currently fixed rate 5% on gross investment income for individuals)
     *
     * @param float $grossInvestmentIncome
     * @return float
     */
    private function calculateTaxInvestment(float $grossInvestmentIncome): float
    {
        // Thuế suất 5% trên tổng thu nhập từ đầu tư
        $taxRate = 0.05;
        return round($grossInvestmentIncome * $taxRate, 0);
    }

    /**
     * Calculates monthly tax based on provided income entry and its type.
     *
     * @param IncomeEntry $incomeEntry
     * @return array
     */
    public function calculateMonthlyTax(IncomeEntry $incomeEntry): array
    {
        $grossIncome = $incomeEntry->gross_income;
        $entryType = $incomeEntry->entry_type ?? 'monthly'; 

        // Nếu là yearly nhưng chỉ nhập lương tháng, tự động nhân 12
        if ($entryType === 'yearly' && $incomeEntry->income_type === 'salary' && $incomeEntry->month) {
            $grossIncome = $grossIncome * 12;
        }

        $otherDeductions = $incomeEntry->other_deductions ?? 0;
        $userId = $incomeEntry->user_id;
        $incomeType = $incomeEntry->income_type;

        $taxPaid = 0;
        $bhxhDeduction = 0; // Khởi tạo
        $personalDeduction = 0; // Khởi tạo
        $dependentDeductionMonthly = 0; // Khởi tạo
        $taxableIncome = 0; // Khởi tạo

        switch ($incomeType) {
            case 'salary':
                // Giảm trừ bản thân
                $personalDeduction = $this->taxParameters[self::PERSONAL_DEDUCTION_KEY];

                // Tính BHXH, BHYT, BHTN
                $bhxhRate = $this->taxParameters[self::SOCIAL_INSURANCE_RATE_KEY];
                $bhxhCap = $this->taxParameters[self::MAX_SOCIAL_INSURANCE_KEY];
                $bhxhSalary = min($grossIncome, $bhxhCap);
                $bhxhDeduction = $bhxhSalary * $bhxhRate;

                // Giảm trừ người phụ thuộc (tạm tính hàng tháng)
                if ($userId) {
                    $user = User::find($userId);
                    if ($user) {
                        // Lấy người phụ thuộc có trạng thái 'active' và đủ điều kiện trong tháng hiện tại
                        $activeDependentsCount = $user->dependents()
                            ->where('status', 'active')
                            ->where(function ($query) use ($incomeEntry) {
                                // Logic này hơi phức tạp nếu incomeEntry chỉ có tháng và năm
                                // Giả sử registration_month và end_month là số tháng trong năm
                                $query->where(function ($q) use ($incomeEntry) {
                                    $q->whereMonth('registration_date', '<=', $incomeEntry->month)
                                        ->whereYear('registration_date', '<=', $incomeEntry->year);
                                })->orWhereNull('registration_date'); // Coi như đăng ký trước đó
    
                                $query->where(function ($q) use ($incomeEntry) {
                                    $q->whereMonth('deactivation_date', '>=', $incomeEntry->month)
                                        ->whereYear('deactivation_date', '>=', $incomeEntry->year);
                                })->orWhereNull('deactivation_date'); // Coi như chưa ngừng hiệu lực
                            })
                            ->count();
                        $dependentDeductionMonthly = $activeDependentsCount * $this->taxParameters[self::DEPENDENT_DEDUCTION_KEY];
                    }
                }

                // Tổng thu nhập chịu thuế
                $assessableIncome = $grossIncome - $bhxhDeduction - $otherDeductions;
                if ($assessableIncome < 0)
                    $assessableIncome = 0;

                // Thu nhập tính thuế (sau giảm trừ)
                $taxableIncome = $assessableIncome - $personalDeduction - $dependentDeductionMonthly;
                if ($taxableIncome < 0)
                    $taxableIncome = 0;

                // Tính thuế TNCN theo lũy tiến
                $taxPaid = $this->calculateProgressiveTaxSalary($taxableIncome);
                break;

            case 'business':
                // Đối với thu nhập kinh doanh, gross_income được xem là doanh thu.
                // Thuế tính trên doanh thu theo tỷ lệ cố định (phương pháp khoán).
                $taxPaid = $this->calculateTaxBusiness($grossIncome);
                break;

            case 'investment':
                // Đối với thu nhập đầu tư, gross_income là tổng thu nhập đầu tư.
                // Thuế tính trên tổng thu nhập theo tỷ lệ cố định.
                $taxPaid = $this->calculateTaxInvestment($grossIncome);
                break;

            default:
                Log::warning("Loại thu nhập không xác định: {$incomeType} cho mục nhập ID: {$incomeEntry->id}");
                $taxPaid = 0;
                break;
        }

        // Lương net ước tính
        // Cần điều chỉnh logic net_income cho từng loại thu nhập nếu cần.
        // Hiện tại, net_income là gross - các khoản trừ bắt buộc (BHXH nếu có) - thuế - các khoản trừ khác.
        $netIncome = $grossIncome - $taxPaid - $bhxhDeduction - $otherDeductions;

        return [
            'gross_income' => round($grossIncome, 0),
            'bhxh_deduction' => round($bhxhDeduction, 0), // BHXH chỉ áp dụng cho lương
            'personal_deduction' => round($personalDeduction, 0), // Chỉ cho lương
            'dependent_deduction' => round($dependentDeductionMonthly, 0), // Chỉ cho lương
            'other_deductions' => round($otherDeductions, 0),
            'assessable_income' => round($grossIncome - $bhxhDeduction - $otherDeductions, 0), // Thu nhập chịu thuế (tạm tính)
            'taxable_income' => round($taxableIncome, 0), // Thu nhập tính thuế (tạm tính)
            'tax_paid' => round($taxPaid, 0),
            'net_income' => round($netIncome, 0),
            'income_type' => $incomeType,

            // Thêm các key để Seeder không lỗi
            'actual_bhxh_deduction' => round($bhxhDeduction, 0),
            'actual_tax_paid' => round($taxPaid, 0),
            'actual_net_income' => round($netIncome, 0),
        ];
    }

    /**
     * Calculates the yearly tax settlement for a user.
     *
     * @param User $user
     * @param int $year
     * @return array
     */
    public function calculateYearlyTaxSettlement(User $user, int $year): array
    {
        $totalSalaryGrossIncome = 0;
        $totalBusinessGrossIncome = 0;
        $totalInvestmentGrossIncome = 0;
        $totalOtherGrossIncome = 0; // Để hứng các loại thu nhập khác không xử lý riêng

        $totalSalaryBhxhDeduction = 0;
        $totalSalaryOtherDeductions = 0; // Chỉ dành cho lương
        $totalBusinessOtherDeductions = 0; // Dành cho kinh doanh nếu có

        $totalTaxPaidProvisionalForSalary = 0;
        $totalTaxPaidProvisionalForBusiness = 0;
        $totalTaxPaidProvisionalForInvestment = 0;
        $totalTaxPaidProvisionalForOther = 0;

        // Lấy tất cả các khoản thu nhập của người dùng trong năm
        $incomeEntries = $user->incomeEntries()
            ->where('year', $year)
            ->get();

        foreach ($incomeEntries as $entry) {
            switch ($entry->income_type) {
                case 'salary':
                    $totalSalaryGrossIncome += $entry->gross_income;
                    $totalSalaryBhxhDeduction += $entry->bhxh_deduction ?? 0;
                    $totalSalaryOtherDeductions += $entry->other_deductions ?? 0;
                    $totalTaxPaidProvisionalForSalary += $entry->tax_paid ?? 0;
                    break;
                case 'business':
                    $totalBusinessGrossIncome += $entry->gross_income;
                    $totalBusinessOtherDeductions += $entry->other_deductions ?? 0; // Chi phí hợp lệ từ kinh doanh
                    $totalTaxPaidProvisionalForBusiness += $entry->tax_paid ?? 0;
                    break;
                case 'investment':
                    $totalInvestmentGrossIncome += $entry->gross_income;
                    $totalTaxPaidProvisionalForInvestment += $entry->tax_paid ?? 0;
                    break;
                default:
                    $totalOtherGrossIncome += $entry->gross_income;
                    $totalTaxPaidProvisionalForOther += $entry->tax_paid ?? 0;
                    break;
            }
        }

        // --- Bắt đầu tính toán cho thu nhập từ tiền lương, tiền công ---
        // Giảm trừ bản thân cả năm
        $personalDeductionYearly = $this->taxParameters[self::PERSONAL_DEDUCTION_KEY] * 12;

        // Giảm trừ người phụ thuộc cả năm
        $totalDependentDeductionYearly = 0;
        $dependents = $user->dependents()->get();
        $dependentMonthlyDeductionAmount = $this->taxParameters[self::DEPENDENT_DEDUCTION_KEY];

        foreach ($dependents as $dependent) {
            $monthsEligible = 0;
            for ($month = 1; $month <= 12; $month++) {
                $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
                $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

                // Người phụ thuộc phải có trạng thái 'active' trong khoảng thời gian có hiệu lực
                // và ngày đăng ký phải trước hoặc trong tháng tính, ngày ngừng hiệu lực phải sau hoặc trong tháng tính
                $isRegisteredBeforeEndOfMonth = $dependent->registration_date && $dependent->registration_date <= $endOfMonth;
                $isDeactivatedAfterStartOfMonth = ($dependent->deactivation_date === null) || ($dependent->deactivation_date && $dependent->deactivation_date >= $startOfMonth);

                if ($dependent->status === 'active' && $isRegisteredBeforeEndOfMonth && $isDeactivatedAfterStartOfMonth) {
                    $monthsEligible++;
                }
            }
            $totalDependentDeductionYearly += ($monthsEligible * $dependentMonthlyDeductionAmount);
        }

        $totalPersonalDeductionsCombined = $personalDeductionYearly + $totalDependentDeductionYearly;

        // Tổng thu nhập chịu thuế từ tiền lương (sau khi trừ BHXH, các khoản giảm khác từ lương)
        $assessableIncomeSalary = $totalSalaryGrossIncome - $totalSalaryBhxhDeduction - $totalSalaryOtherDeductions;
        if ($assessableIncomeSalary < 0)
            $assessableIncomeSalary = 0;

        // Tổng thu nhập tính thuế từ tiền lương (sau khi trừ giảm trừ gia cảnh)
        $totalTaxableIncomeSalary = $assessableIncomeSalary - $totalPersonalDeductionsCombined;
        if ($totalTaxableIncomeSalary < 0)
            $totalTaxableIncomeSalary = 0;

        // Tổng thuế phải nộp từ tiền lương theo lũy tiến
        $totalTaxRequiredSalary = $this->calculateProgressiveTaxSalary($totalTaxableIncomeSalary);
        // --- Kết thúc tính toán cho thu nhập từ tiền lương, tiền công ---


        // --- Tính toán cho thu nhập từ kinh doanh ---
        // Thu nhập kinh doanh tính theo tỷ lệ trên doanh thu
        // (Lưu ý: Nếu có 'other_deductions' cho kinh doanh, bạn có thể trừ chúng ở đây trước khi tính thuế)
        $businessRevenueForTax = $totalBusinessGrossIncome - $totalBusinessOtherDeductions; // Giả sử other_deductions là chi phí hợp lệ
        if ($businessRevenueForTax < 0)
            $businessRevenueForTax = 0;
        $totalTaxRequiredBusiness = $this->calculateTaxBusiness($businessRevenueForTax);
        // --- Kết thúc tính toán cho thu nhập từ kinh doanh ---


        // --- Tính toán cho thu nhập từ đầu tư ---
        // Thu nhập đầu tư tính theo tỷ lệ trên tổng thu nhập đầu tư
        $totalTaxRequiredInvestment = $this->calculateTaxInvestment($totalInvestmentGrossIncome);
        // --- Kết thúc tính toán cho thu nhập từ đầu tư ---

        // --- Tổng hợp quyết toán cuối cùng ---
        // Tổng thu nhập tính thuế CHUNG cho quyết toán cuối năm (chỉ từ tiền lương, tiền công)
        // Các loại thu nhập khác tính thuế riêng biệt và không cộng dồn vào TNCT để tính theo lũy tiến
        $totalTaxableIncomeForSettlement = $totalTaxableIncomeSalary;

        // Tổng thuế phải nộp của tất cả các loại thu nhập
        $totalTaxRequiredYearly = $totalTaxRequiredSalary + $totalTaxRequiredBusiness + $totalTaxRequiredInvestment + $totalTaxPaidProvisionalForOther; // Các loại khác coi như thuế đã nộp là thuế phải nộp

        // Tổng thuế đã tạm nộp của tất cả các loại thu nhập
        $totalTaxPaidProvisionalOverall = $totalTaxPaidProvisionalForSalary + $totalTaxPaidProvisionalForBusiness + $totalTaxPaidProvisionalForInvestment + $totalTaxPaidProvisionalForOther;

        // Số thuế còn phải nộp thêm hoặc được hoàn lại
        $taxToPayOrRefund = $totalTaxRequiredYearly - $totalTaxPaidProvisionalOverall;


        return [
            'year' => $year,
            // Tổng hợp chung
            'total_gross_income' => round($totalSalaryGrossIncome + $totalBusinessGrossIncome + $totalInvestmentGrossIncome + $totalOtherGrossIncome, 0),
            'total_bhxh_deduction' => round($totalSalaryBhxhDeduction, 0), // Chỉ BHXH từ lương
            'total_other_deductions' => round($totalSalaryOtherDeductions + $totalBusinessOtherDeductions, 0), // Tổng các giảm trừ khác từ lương và kinh doanh

            // Các khoản giảm trừ gia cảnh (chỉ áp dụng cho thu nhập từ lương, tiền công)
            'total_personal_deductions' => round($totalPersonalDeductionsCombined, 0),

            // Thu nhập tính thuế cho quyết toán cuối năm (chỉ từ lương)
            'total_taxable_income_yearly' => round($totalTaxableIncomeForSettlement, 0), // **ĐÃ THÊM KEY NÀY!**

            // Tổng thuế đã tạm nộp các loại
            'total_tax_paid_provisional' => round($totalTaxPaidProvisionalOverall, 0),

            // Tổng thuế phải nộp của tất cả các loại thu nhập
            'total_tax_required_yearly' => round($totalTaxRequiredYearly, 0),

            // Số thuế còn phải nộp hoặc được hoàn lại
            'tax_to_pay_or_refund' => round($taxToPayOrRefund, 0),

            // Chi tiết theo từng loại thu nhập (có thể hữu ích cho báo cáo chi tiết)
            'breakdown' => [
                'salary' => [
                    'gross_income' => round($totalSalaryGrossIncome, 0),
                    'assessable_income' => round($assessableIncomeSalary, 0),
                    'taxable_income' => round($totalTaxableIncomeSalary, 0),
                    'tax_required' => round($totalTaxRequiredSalary, 0),
                    'tax_paid_provisional' => round($totalTaxPaidProvisionalForSalary, 0),
                ],
                'business' => [
                    'gross_income' => round($totalBusinessGrossIncome, 0),
                    'tax_required' => round($totalTaxRequiredBusiness, 0),
                    'tax_paid_provisional' => round($totalTaxPaidProvisionalForBusiness, 0),
                ],
                'investment' => [
                    'gross_income' => round($totalInvestmentGrossIncome, 0),
                    'tax_required' => round($totalTaxRequiredInvestment, 0),
                    'tax_paid_provisional' => round($totalTaxPaidProvisionalForInvestment, 0),
                ],
            ]
        ];
    }

    /**
     * Tính toán tổng thu nhập và thuế của user tại một công ty trong một năm.
     *
     * @param User $user
     * @param int $year
     * @param int $companyId
     * @return array
     */
    public function calculateCompanyYearlyTax(User $user, int $year, int $companyId): array
    {
        $incomeEntries = $user->incomeEntries()
            ->where('year', $year)
            ->where('income_source_id', $companyId)
            ->get();

        $totalGrossIncome = 0;
        $totalTaxPaid = 0;
        $totalBhxhDeduction = 0;
        $totalOtherDeductions = 0;

        foreach ($incomeEntries as $entry) {
            $totalGrossIncome += $entry->gross_income;
            $totalTaxPaid += $entry->tax_paid ?? 0;
            $totalBhxhDeduction += $entry->bhxh_deduction ?? 0;
            $totalOtherDeductions += $entry->other_deductions ?? 0;
        }

        return [
            'total_gross_income' => round($totalGrossIncome, 0),
            'total_tax_paid' => round($totalTaxPaid, 0),
            'total_bhxh_deduction' => round($totalBhxhDeduction, 0),
            'total_other_deductions' => round($totalOtherDeductions, 0),
            // Có thể bổ sung các trường khác nếu cần
        ];
    }

    /**
     * Tính toán lương/thực nhận và thuế cho cả hai chiều Gross → Net và Net → Gross (bước 1: chỉ Gross → Net)
     * @param array $data
     * @return array
     */
    public function calculateMonthlyTaxV2(array $data): array
    {
        // data từ form
        $direction = $data['calculation_direction'] ?? 'gross_to_net';
        $region = (int) ($data['region'] ?? 1);
        $insuranceType = $data['insurance_salary_type'] ?? 'official';
        $insuranceCustom = isset($data['insurance_salary_custom']) ? (float) $data['insurance_salary_custom'] : null;
        $dependents = (int) ($data['dependents'] ?? 0);
        $grossIncome = isset($data['gross_income']) ? (float) $data['gross_income'] : 0;
        $netIncome = isset($data['net_income']) ? (float) $data['net_income'] : 0;
        $otherDeductions = isset($data['other_deductions']) ? (float) $data['other_deductions'] : 0;
        $incomeType = $data['income_type'] ?? 'salary';
        $entryType = $data['entry_type'] ?? 'monthly';

        // Nếu là tính cho cả năm, thì giảm trừ bản thân và người phụ thuộc cũng phải tính cho cả năm
        $personalDeductionMultiplier = ($entryType === 'yearly') ? 12 : 1;
        $dependentDeductionMultiplier = ($entryType === 'yearly') ? 12 : 1;

        if ($direction === 'net_to_gross') {
            // Lặp để tìm gross_income phù hợp
            $targetNet = $netIncome;
            $minSalaryByRegion = [
                1 => 4700000,
                2 => 4200000,
                3 => 3700000,
                4 => 3300000,
            ];
            $minSalary = $minSalaryByRegion[$region] ?? 4700000;
            $personalDeduction = ($this->taxParameters[self::PERSONAL_DEDUCTION_KEY] ?? 11000000) * $personalDeductionMultiplier;
            $dependentDeduction = ($this->taxParameters[self::DEPENDENT_DEDUCTION_KEY] ?? 4400000) * $dependentDeductionMultiplier;
            $totalDependentDeduction = $dependents * $dependentDeduction;
            $bhxhCap = $this->taxParameters[self::MAX_SOCIAL_INSURANCE_KEY] ?? 29800000;
            $maxIter = 100;
            $tolerance = 1;
            $guessGross = $targetNet + 10000000; // Ước lượng ban đầu
            $found = false;
            for ($i = 0; $i < $maxIter; $i++) {
                // Xác định lương đóng bảo hiểm
                if ($insuranceType === 'official') {
                    $insuranceSalary = $guessGross;
                } elseif ($insuranceType === 'custom' && $insuranceCustom > 0) {
                    $insuranceSalary = $insuranceCustom;
                } else {
                    $insuranceSalary = $guessGross;
                }
                $insuranceSalary = max($insuranceSalary, $minSalary);
                $insuranceSalary = min($insuranceSalary, $bhxhCap);
                $bhxh = $insuranceSalary * 0.08;
                $bhyt = $insuranceSalary * 0.015;
                $bhtn = $insuranceSalary * 0.01;
                $totalInsurance = $bhxh + $bhyt + $bhtn;
                $assessableIncome = $guessGross - $totalInsurance - $personalDeduction - $totalDependentDeduction - $otherDeductions;
                if ($assessableIncome < 0)
                    $assessableIncome = 0;
                $taxPaid = 0;
                $taxBracketsDetail = [];
                if ($incomeType === 'salary') {
                    $taxPaid = 0;
                    $remaining = $assessableIncome;
                    foreach ($this->taxBrackets as $bracket) {
                        $incomeFrom = $bracket['income_from'];
                        $incomeTo = $bracket['income_to'];
                        $taxRate = $bracket['tax_rate'];
                        $label = $bracket['label'] ?? (isset($incomeTo) ? ("Trên " . number_format($incomeFrom) . " đến " . number_format($incomeTo)) : ("Trên " . number_format($incomeFrom)));
                        if ($remaining > 0 && $assessableIncome > $incomeFrom) {
                            $amountInBracket = 0;
                            if ($incomeTo === null) {
                                $amountInBracket = $remaining;
                            } else {
                                $amountInBracket = min($remaining, $incomeTo - $incomeFrom);
                            }
                            $taxForBracket = $amountInBracket * $taxRate;
                            $taxBracketsDetail[] = [
                                'label' => $label,
                                'rate' => $taxRate * 100,
                                'amount' => round($taxForBracket, 0),
                            ];
                            $taxPaid += $taxForBracket;
                            $remaining -= $amountInBracket;
                        } else {
                            $taxBracketsDetail[] = [
                                'label' => $label,
                                'rate' => $taxRate * 100,
                                'amount' => 0,
                            ];
                        }
                    }
                    $taxPaid = round($taxPaid, 0);
                }
                $net = $guessGross - $totalInsurance - $taxPaid - $otherDeductions;
                if (abs($net - $targetNet) <= $tolerance) {
                    $found = true;
                    break;
                }
                // Điều chỉnh guessGross
                $guessGross += ($targetNet - $net) * 1.05;
                if ($guessGross < 0)
                    $guessGross = $targetNet; // tránh âm
            }
            if (!$found) {
                return [
                    'actual_bhxh_deduction' => 0,
                    'actual_tax_paid' => 0,
                    'actual_net_income' => $targetNet,
                    'actual_gross_income' => 0,
                    'error' => 'Không thể tính ngược Gross phù hợp với Net đã nhập!'
                ];
            }
            // 8. Chi phí người sử dụng lao động
            $bhxh_employer = $insuranceSalary * 0.17;
            $bhyt_employer = $insuranceSalary * 0.03;
            $bhtn_employer = $insuranceSalary * 0.01;
            $bhtnld = $insuranceSalary * 0.005;
            $tong_chi_phi = $grossIncome + $bhxh_employer + $bhyt_employer + $bhtn_employer + $bhtnld;

            return [
                'actual_bhxh_deduction' => round($totalInsurance, 0),
                'actual_tax_paid' => round($taxPaid, 0),
                'actual_net_income' => round($net, 0),
                'actual_gross_income' => round($guessGross, 0),
                'personal_deduction' => $personalDeduction,
                'dependent_deduction' => $totalDependentDeduction,
                'region' => $region,
                'insurance_salary' => $insuranceSalary,
                'bhxh' => round($bhxh, 0),
                'bhyt' => round($bhyt, 0),
                'bhtn' => round($bhtn, 0),
                'thu_nhap_truoc_thue' => round($grossIncome - $totalInsurance, 0),
                'giam_tru_ban_than' => $personalDeduction,
                'giam_tru_phu_thuoc' => $totalDependentDeduction,
                'thu_nhap_chiu_thue' => round($assessableIncome, 0),
                'tax_brackets_detail' => $taxBracketsDetail,
                'bhxh_employer' => round($bhxh_employer, 0),
                'bhyt_employer' => round($bhyt_employer, 0),
                'bhtn_employer' => round($bhtn_employer, 0),
                'bhtnld' => round($bhtnld, 0),
                'tong_chi_phi' => round($tong_chi_phi, 0),
                'error' => null
            ];
        }


        $minSalaryByRegion = [
            1 => 4700000, // Vùng I
            2 => 4200000, // Vùng II
            3 => 3700000, // Vùng III
            4 => 3300000, // Vùng IV
        ];
        $minSalary = $minSalaryByRegion[$region] ?? 4700000;

        // 2. Xác định lương đóng bảo hiểm
        if ($insuranceType === 'official') {
            $insuranceSalary = $grossIncome;
        } elseif ($insuranceType === 'custom' && $insuranceCustom > 0) {
            $insuranceSalary = $insuranceCustom;
        } else {
            $insuranceSalary = $grossIncome;
        }
        // Lương đóng bảo hiểm không được thấp hơn lương tối thiểu vùng
        $insuranceSalary = max($insuranceSalary, $minSalary);
        // Trần lương đóng bảo hiểm
        $bhxhCap = $this->taxParameters[self::MAX_SOCIAL_INSURANCE_KEY] ?? 29800000;
        $insuranceSalary = min($insuranceSalary, $bhxhCap);

        // 3. Tính các khoản bảo hiểm (chuẩn mới: 8% BHXH, 1.5% BHYT, 1% BHTN)
        $bhxh = $insuranceSalary * 0.08;
        $bhyt = $insuranceSalary * 0.015;
        $bhtn = $insuranceSalary * 0.01;
        $totalInsurance = $bhxh + $bhyt + $bhtn;

        // 4. Giảm trừ bản thân và người phụ thuộc
        $personalDeduction = ($this->taxParameters[self::PERSONAL_DEDUCTION_KEY] ?? 11000000) * $personalDeductionMultiplier;
        $dependentDeduction = ($this->taxParameters[self::DEPENDENT_DEDUCTION_KEY] ?? 4400000) * $dependentDeductionMultiplier;
        $totalDependentDeduction = $dependents * $dependentDeduction;

        // 5. Thu nhập chịu thuế
        $assessableIncome = $grossIncome - $totalInsurance - $personalDeduction - $totalDependentDeduction - $otherDeductions;
        if ($assessableIncome < 0)
            $assessableIncome = 0;

        // 6. Tính thuế TNCN lũy tiến
        $taxPaid = 0;
        $taxBracketsDetail = [];
        if ($incomeType === 'salary') {
            $taxPaid = 0;
            $remaining = $assessableIncome;
            foreach ($this->taxBrackets as $bracket) {
                $incomeFrom = $bracket['income_from'];
                $incomeTo = $bracket['income_to'];
                $taxRate = $bracket['tax_rate'];
                $label = $bracket['label'] ?? (isset($incomeTo) ? ("Trên " . number_format($incomeFrom) . " đến " . number_format($incomeTo)) : ("Trên " . number_format($incomeFrom)));
                if ($remaining > 0 && $assessableIncome > $incomeFrom) {
                    $amountInBracket = 0;
                    if ($incomeTo === null) {
                        $amountInBracket = $remaining;
                    } else {
                        $amountInBracket = min($remaining, $incomeTo - $incomeFrom);
                    }
                    $taxForBracket = $amountInBracket * $taxRate;
                    $taxBracketsDetail[] = [
                        'label' => $label,
                        'rate' => $taxRate * 100,
                        'amount' => round($taxForBracket, 0),
                    ];
                    $taxPaid += $taxForBracket;
                    $remaining -= $amountInBracket;
                } else {
                    $taxBracketsDetail[] = [
                        'label' => $label,
                        'rate' => $taxRate * 100,
                        'amount' => 0,
                    ];
                }
            }
            $taxPaid = round($taxPaid, 0);
        }

        // 7. Tính lương thực nhận (Net)
        $net = $grossIncome - $totalInsurance - $taxPaid - $otherDeductions;
        if ($net < 0)
            $net = 0;

        // 8. Chi phí người sử dụng lao động
        $bhxh_employer = $insuranceSalary * 0.17;
        $bhyt_employer = $insuranceSalary * 0.03;
        $bhtn_employer = $insuranceSalary * 0.01;
        $bhtnld = $insuranceSalary * 0.005;
        $tong_chi_phi = $grossIncome + $bhxh_employer + $bhyt_employer + $bhtn_employer + $bhtnld;

        return [
            'actual_bhxh_deduction' => round($totalInsurance, 0),
            'actual_tax_paid' => round($taxPaid, 0),
            'actual_net_income' => round($net, 0),
            'actual_gross_income' => round($grossIncome, 0),
            'personal_deduction' => $personalDeduction,
            'dependent_deduction' => $totalDependentDeduction,
            'region' => $region,
            'insurance_salary' => $insuranceSalary,
            'bhxh' => round($bhxh, 0),
            'bhyt' => round($bhyt, 0),
            'bhtn' => round($bhtn, 0),
            'thu_nhap_truoc_thue' => round($grossIncome - $totalInsurance, 0),
            'giam_tru_ban_than' => $personalDeduction,
            'giam_tru_phu_thuoc' => $totalDependentDeduction,
            'thu_nhap_chiu_thue' => round($assessableIncome, 0),
            'tax_brackets_detail' => $taxBracketsDetail,
            'bhxh_employer' => round($bhxh_employer, 0),
            'bhyt_employer' => round($bhyt_employer, 0),
            'bhtn_employer' => round($bhtn_employer, 0),
            'bhtnld' => round($bhtnld, 0),
            'tong_chi_phi' => round($tong_chi_phi, 0),
            'entry_type' => $entryType,
            'error' => null
        ];
    }

    /**
     * Provides a yearly breakdown of income and deductions per income source.
     *
     * @param User $user
     * @param int $year
     * @return \Illuminate\Support\Collection
     */
    public function getYearlyBreakdownBySource(User $user, int $year)
    {
        // Lấy tất cả các khoản thu nhập trong năm và thông tin nguồn liên quan
        $incomeEntries = $user->incomeEntries()
            ->where('year', $year)
            ->with('incomeSource')
            ->get();

        // Nhóm các khoản thu nhập theo ID của nguồn và tổng hợp dữ liệu
        $breakdown = $incomeEntries->groupBy('income_source_id')->map(function ($entries, $sourceId) {
            $source = $entries->first()->incomeSource;

            if (!$source) {
                return null; // Bỏ qua nếu không tìm thấy nguồn
            }

            $totalGross = $entries->sum('gross_income');
            $totalBhxh = $entries->sum('bhxh_deduction');
            $totalOtherDeductions = $entries->sum('other_deductions');
            $totalTaxPaid = $entries->sum('tax_paid');

            $taxRequired = 0;
            // Tính thuế phải nộp trực tiếp cho các nguồn không phải lương
            if ($source->income_type === 'business') {
                $taxRequired = $this->calculateTaxBusiness($totalGross - $totalOtherDeductions);
            } elseif ($source->income_type === 'investment') {
                $taxRequired = $this->calculateTaxInvestment($totalGross);
            }
            // Đối với lương, thuế được tính trên tổng hợp nên sẽ hiển thị riêng

            return [
                'source_name' => $source->name,
                'income_type' => $source->income_type,
                'total_gross' => $totalGross,
                'total_bhxh' => $totalBhxh,
                'total_other_deductions' => $totalOtherDeductions,
                'total_tax_paid' => $totalTaxPaid,
                'tax_required' => $taxRequired, // Chỉ chính xác cho các nguồn không phải lương
            ];
        })->filter(); // Lọc bỏ các kết quả null

        return $breakdown;
    }
}