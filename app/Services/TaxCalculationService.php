<?php

namespace App\Services;

use App\Models\SystemConfig;
use App\Models\TaxBracket;
use App\Models\User;
use Carbon\Carbon;

class TaxCalculationService
{
    /**
     * Lấy giá trị cấu hình hệ thống theo key và ngày hiệu lực.
     * Phương thức này sẽ tìm cấu hình có 'effective_date' nhỏ hơn hoặc bằng ngày cho trước
     * và lấy bản ghi mới nhất (theo effective_date giảm dần).
     *
     * @param string $key Tên của cấu hình (ví dụ: 'personal_deduction_amount')
     * @param Carbon|null $date Ngày cần áp dụng cấu hình (mặc định là ngày hiện tại)
     * @return float|null Giá trị của cấu hình, hoặc null nếu không tìm thấy
     */
    private function getSystemConfigValue(string $key, ?Carbon $date = null): ?float
    {
        $date = $date ?? Carbon::now();
        $config = SystemConfig::where('key', $key)
                            ->where('effective_date', '<=', $date)
                            ->orderBy('effective_date', 'desc')
                            ->first();
        return $config ? (float)$config->value : null;
    }

    /**
     * Lấy tất cả các bậc thuế có hiệu lực tại một thời điểm nhất định.
     * Phương thức này sẽ tìm các bậc thuế có 'effective_date' nhỏ hơn hoặc bằng ngày cho trước
     * và lấy bộ bậc thuế mới nhất (theo effective_date giảm dần).
     *
     * @param Carbon|null $date Ngày cần áp dụng biểu thuế (mặc định là ngày hiện tại)
     * @return \Illuminate\Database\Eloquent\Collection Tập hợp các đối tượng TaxBracket
     */
    private function getTaxBrackets(?Carbon $date = null)
    {
        $date = $date ?? Carbon::now();
        return TaxBracket::where('effective_date', '<=', $date)
                        ->orderBy('effective_date', 'desc') // Sắp xếp để lấy phiên bản mới nhất
                        ->orderBy('min_income', 'asc')    // Sắp xếp theo mức thu nhập để đảm bảo đúng thứ tự bậc
                        ->get()
                        ->unique('level'); // Đảm bảo chỉ lấy một phiên bản cho mỗi bậc thuế (phiên bản mới nhất)
    }

    /**
     * Tính toán các khoản đóng bảo hiểm bắt buộc của người lao động.
     * Bao gồm BHXH, BHYT, BHTN. Mức đóng có thể bị giới hạn bởi mức trần.
     *
     * @param float $grossSalary Lương Gross dùng để tính bảo hiểm
     * @param Carbon|null $date Ngày tính (để lấy tỷ lệ và mức trần hiệu lực)
     * @return float Tổng số tiền bảo hiểm bắt buộc phải đóng
     */
    public function calculateSocialInsuranceContribution(float $grossSalary, ?Carbon $date = null): float
    {
        $date = $date ?? Carbon::now();

        // Lấy các tỷ lệ đóng bảo hiểm từ cấu hình hệ thống (hoặc giá trị mặc định nếu không tìm thấy)
        $socialInsuranceRate = $this->getSystemConfigValue('social_insurance_rate', $date) ?? 0.08;
        $healthInsuranceRate = $this->getSystemConfigValue('health_insurance_rate', $date) ?? 0.015;
        $unemploymentInsuranceRate = $this->getSystemConfigValue('unemployment_insurance_rate', $date) ?? 0.01;
        $maxSocialInsuranceBaseSalary = $this->getSystemConfigValue('max_social_insurance_base_salary', $date) ?? 36000000;

        $totalInsuranceRate = $socialInsuranceRate + $healthInsuranceRate + $unemploymentInsuranceRate;

        // Lương đóng bảo hiểm không vượt quá mức trần quy định
        $salaryForInsurance = min($grossSalary, $maxSocialInsuranceBaseSalary);

        return round($salaryForInsurance * $totalInsuranceRate);
    }

    /**
     * Tính tổng các khoản giảm trừ của cá nhân.
     *
     * @param float $personalDeduction Giảm trừ bản thân
     * @param float $dependentDeduction Giảm trừ người phụ thuộc
     * @param float $socialInsuranceContribution Bảo hiểm bắt buộc đã đóng
     * @param float $charityDeduction Giảm trừ từ thiện, nhân đạo, khuyến học
     * @return float Tổng số tiền giảm trừ
     */
    public function calculateTotalDeductions(
        float $personalDeduction,
        float $dependentDeduction,
        float $socialInsuranceContribution,
        float $charityDeduction = 0
    ): float {
        return $personalDeduction + $dependentDeduction + $socialInsuranceContribution + $charityDeduction;
    }

    /**
     * Tính thuế TNCN lũy tiến từng phần dựa trên thu nhập tính thuế.
     *
     * @param float $taxableIncome Thu nhập tính thuế
     * @param Carbon|null $date Ngày áp dụng biểu thuế (để chọn đúng phiên bản biểu thuế)
     * @return float Số thuế TNCN phải nộp
     */
    public function calculatePIT(float $taxableIncome, ?Carbon $date = null): float
    {
        if ($taxableIncome <= 0) {
            return 0;
        }

        $taxBrackets = $this->getTaxBrackets($date);
        $totalTax = 0;
        $remainingTaxableIncome = $taxableIncome;

        foreach ($taxBrackets as $bracket) {
            if ($remainingTaxableIncome <= 0) {
                break;
            }

            $minIncomeOfBracket = $bracket->min_income;
            $maxIncomeOfBracket = $bracket->max_income;
            $taxRate = $bracket->tax_rate;

            $incomeInThisBracket = 0;

            // Tính phần thu nhập nằm trong bậc hiện tại
            // Phần thu nhập trong bậc = min(remaining, max_of_bracket - (min_of_bracket - 1))
            $upperBoundForCalculation = ($maxIncomeOfBracket !== null) ? $maxIncomeOfBracket : PHP_INT_MAX;
            // Điều chỉnh cho bậc 1 bắt đầu từ 0
            $lowerBoundForCalculation = ($minIncomeOfBracket > 0) ? ($minIncomeOfBracket - 1) : 0;

            $incomeInThisBracket = min($remainingTaxableIncome, $upperBoundForCalculation - $lowerBoundForCalculation);

            // Đảm bảo không tính thuế cho phần thu nhập âm hoặc không hợp lệ
            $incomeInThisBracket = max(0, $incomeInThisBracket);

            $totalTax += $incomeInThisBracket * $taxRate;
            $remainingTaxableIncome -= $incomeInThisBracket;
        }

        return round($totalTax);
    }

    /**
     * Hàm tổng hợp tính toán toàn bộ thuế TNCN và lương Net cho một tháng cụ thể.
     *
     * @param User $user Đối tượng người dùng để lấy thông tin người phụ thuộc
     * @param float $grossSalary Lương Gross hàng tháng
     * @param float $otherTaxableIncome Các khoản thu nhập chịu thuế khác (ví dụ: hoa hồng, thưởng)
     * @param float $nonTaxableIncome Các khoản thu nhập miễn thuế (ví dụ: tiền ăn trưa, công tác phí khoán)
     * @param float $deductionCharity Khoản đóng góp từ thiện, nhân đạo, khuyến học
     * @param Carbon $declarationMonth Tháng khai báo (để lấy cấu hình hiệu lực tại tháng đó)
     * @param bool $returnDetailedSteps Nếu true, trả về chi tiết các bước tính toán
     * @return array Kết quả tính toán chi tiết
     */
    public function calculateMonthlyPIT(
        User $user,
        float $grossSalary,
        float $otherTaxableIncome = 0,
        float $nonTaxableIncome = 0,
        float $deductionCharity = 0,
        Carbon $declarationMonth = null,
        bool $returnDetailedSteps = false
    ): array {
        $declarationMonth = $declarationMonth ?? Carbon::now()->startOfMonth();
        $steps = []; // Mảng lưu trữ các bước tính toán chi tiết

        // 1. Lấy cấu hình hệ thống cho tháng hiện tại
        $personalDeductionAmount = $this->getSystemConfigValue('personal_deduction_amount', $declarationMonth) ?? 11000000;
        $dependentDeductionAmount = $this->getSystemConfigValue('dependent_deduction_amount', $declarationMonth) ?? 4400000;

        $steps[] = [
            'step' => 1,
            'description' => 'Lấy các mức cấu hình hiện hành từ hệ thống:',
            'details' => [
                'Mức giảm trừ bản thân: ' . number_format($personalDeductionAmount) . ' VNĐ/tháng',
                'Mức giảm trừ người phụ thuộc: ' . number_format($dependentDeductionAmount) . ' VNĐ/người/tháng',
            ],
            'value' => null
        ];

        // 2. Tính tổng thu nhập chịu thuế
        $totalTaxableIncomeBeforeExemption = $grossSalary + $otherTaxableIncome;
        $totalTaxableIncome = $totalTaxableIncomeBeforeExemption - $nonTaxableIncome;
        $steps[] = [
            'step' => 2,
            'description' => 'Tổng thu nhập chịu thuế trong tháng (Lương Gross + Thu nhập khác chịu thuế - Thu nhập miễn thuế):',
            'details' => [
                number_format($grossSalary) . ' (Gross) + ' . number_format($otherTaxableIncome) . ' (Khác chịu thuế) - ' . number_format($nonTaxableIncome) . ' (Miễn thuế) = ' . number_format($totalTaxableIncome) . ' VNĐ'
            ],
            'value' => $totalTaxableIncome
        ];

        // 3. Tính các khoản đóng bảo hiểm bắt buộc
        $socialInsuranceContribution = $this->calculateSocialInsuranceContribution($grossSalary, $declarationMonth);
        $steps[] = [
            'step' => 3,
            'description' => 'Các khoản đóng bảo hiểm bắt buộc (phần người lao động đóng):',
            'details' => [
                'Tính trên lương Gross: ' . number_format($grossSalary) . ' VNĐ',
                'Số tiền đóng BHXH, BHYT, BHTN: ' . number_format($socialInsuranceContribution) . ' VNĐ'
            ],
            'value' => $socialInsuranceContribution
        ];

        // 4. Tính giảm trừ bản thân
        $personalDeduction = $personalDeductionAmount;
        $steps[] = [
            'step' => 4,
            'description' => 'Khoản giảm trừ bản thân (cố định hàng tháng):',
            'details' => [
                number_format($personalDeduction) . ' VNĐ'
            ],
            'value' => $personalDeduction
        ];

        // 5. Tính giảm trừ người phụ thuộc
        $validDependentsCount = $user->getValidDependentsCount($declarationMonth->year, $declarationMonth->month);
        $dependentDeduction = $validDependentsCount * $dependentDeductionAmount;
        $steps[] = [
            'step' => 5,
            'description' => 'Khoản giảm trừ người phụ thuộc:',
            'details' => [
                $validDependentsCount . ' (số người phụ thuộc hợp lệ) x ' . number_format($dependentDeductionAmount) . ' (mức giảm trừ/người) = ' . number_format($dependentDeduction) . ' VNĐ'
            ],
            'value' => $dependentDeduction
        ];

        // 6. Tính tổng các khoản giảm trừ
        $totalDeduction = $this->calculateTotalDeductions(
            $personalDeduction,
            $dependentDeduction,
            $socialInsuranceContribution,
            $deductionCharity
        );
        $steps[] = [
            'step' => 6,
            'description' => 'Tổng các khoản giảm trừ (Bản thân + Người phụ thuộc + BH Bắt buộc + Từ thiện):',
            'details' => [
                number_format($personalDeduction) . ' + ' . number_format($dependentDeduction) . ' + ' . number_format($socialInsuranceContribution) . ' + ' . number_format($deductionCharity) . ' = ' . number_format($totalDeduction) . ' VNĐ'
            ],
            'value' => $totalDeduction
        ];

        // 7. Tính thu nhập tính thuế
        $taxableIncome = max(0, $totalTaxableIncome - $totalDeduction);
        $steps[] = [
            'step' => 7,
            'description' => 'Thu nhập tính thuế (Tổng thu nhập chịu thuế - Tổng giảm trừ):',
            'details' => [
                number_format($totalTaxableIncome) . ' - ' . number_format($totalDeduction) . ' = ' . number_format($taxableIncome) . ' VNĐ (Nếu nhỏ hơn 0 thì là 0 VNĐ)'
            ],
            'value' => $taxableIncome
        ];

        // 8. Áp dụng biểu thuế lũy tiến từng phần
        $pitAmount = $this->calculatePIT($taxableIncome, $declarationMonth);
        $taxBrackets = $this->getTaxBrackets($declarationMonth); // Lấy lại để hiển thị chi tiết
        $remainingTaxableIncomeForBreakdown = $taxableIncome;
        $taxCalculationBreakdown = [];

        foreach ($taxBrackets as $bracket) {
            if ($remainingTaxableIncomeForBreakdown <= 0) break;

            $minIncomeOfBracket = $bracket->min_income;
            $maxIncomeOfBracket = $bracket->max_income;
            $taxRate = $bracket->tax_rate;

            $upperBoundForCalculation = ($maxIncomeOfBracket !== null) ? $maxIncomeOfBracket : PHP_INT_MAX;
            $lowerBoundForCalculation = ($minIncomeOfBracket > 0) ? ($minIncomeOfBracket - 1) : 0;

            $incomeInThisBracket = min($remainingTaxableIncomeForBreakdown, $upperBoundForCalculation - $lowerBoundForCalculation);
            $incomeInThisBracket = max(0, $incomeInThisBracket);

            if ($incomeInThisBracket > 0) {
                $taxInBracket = $incomeInThisBracket * $taxRate;
                $annualTaxCalculationBreakdown[] = [
                    'bracket' => ($maxIncomeOfBracket === null ? 'Từ ' . number_format($minIncomeOfBracket) . ' VNĐ trở lên' : number_format($minIncomeOfBracket) . ' - ' . number_format($maxIncomeOfBracket) . ' VNĐ'),
                    'income_in_bracket' => number_format($incomeInThisBracket),
                    'rate' => ($taxRate * 100) . '%',
                    'tax_amount' => number_format($taxInBracket)
                ];
                $remainingTaxableIncomeForBreakdown -= $incomeInThisBracket;
            }
        }

        $steps[] = [
            'step' => 8,
            'description' => 'Thuế TNCN phải nộp (áp dụng biểu thuế lũy tiến từng phần):',
            'details' => $taxCalculationBreakdown,
            'value' => $pitAmount
        ];

        // 9. Tính lương Net thực nhận
        $netSalary = $grossSalary - $socialInsuranceContribution - $pitAmount;
        $steps[] = [
            'step' => 9,
            'description' => 'Lương Net thực nhận (Lương Gross - Bảo hiểm bắt buộc - Thuế TNCN):',
            'details' => [
                number_format($grossSalary) . ' - ' . number_format($socialInsuranceContribution) . ' - ' . number_format($pitAmount) . ' = ' . number_format($netSalary) . ' VNĐ'
            ],
            'value' => $netSalary
        ];

        $results = [
            'gross_salary' => $grossSalary,
            'other_taxable_income' => $otherTaxableIncome,
            'non_taxable_income' => $nonTaxableIncome,
            'deduction_charity' => $deductionCharity,
            'total_taxable_income' => $totalTaxableIncome,
            'social_insurance_contribution' => $socialInsuranceContribution,
            'personal_deduction' => $personalDeduction,
            'dependent_deduction' => $dependentDeduction,
            'total_deduction' => $totalDeduction,
            'taxable_income' => $taxableIncome,
            'pit_amount' => $pitAmount,
            'net_salary' => $netSalary,
        ];

        if ($returnDetailedSteps) {
            $results['steps'] = $steps;
        }

        return $results;
    }

    /**
     * Tính toán quyết toán thuế TNCN cho cả năm.
     * Tổng hợp dữ liệu từ các khai báo thu nhập hàng tháng và tính toán lại thuế cho cả năm.
     *
     * @param User $user Đối tượng người dùng
     * @param int $year Năm cần quyết toán
     * @param bool $returnDetailedSteps Nếu true, trả về chi tiết các bước tính toán
     * @return array Kết quả quyết toán
     */
    public function calculateAnnualPIT(User $user, int $year, bool $returnDetailedSteps = false): array
    {
        $steps = []; // Mảng lưu trữ các bước tính toán cho quyết toán năm

        $startDate = Carbon::create($year, 1, 1)->startOfMonth();
        $endDate = Carbon::create($year, 12, 31)->endOfMonth();

        // Lấy tất cả các khai báo thu nhập trong năm của người dùng
        $declarations = $user->incomeDeclarations()
                             ->whereBetween('declaration_month', [$startDate, $endDate])
                             ->get();

        // Khởi tạo các tổng cộng
        $annualGrossSalary = $declarations->sum('gross_salary');
        $annualOtherTaxableIncome = $declarations->sum('other_taxable_income');
        $annualNonTaxableIncome = $declarations->sum('non_taxable_income');
        $annualDeductionCharity = $declarations->sum('deduction_charity');
        $annualSocialInsuranceContribution = $declarations->sum('social_insurance_contribution');
        $annualTaxDeductedAtSource = $declarations->sum('tax_deducted_at_source'); // Thuế đã khấu trừ tại nguồn hàng tháng

        // Nếu không có khai báo nào trong năm, trả về kết quả rỗng
        if ($declarations->isEmpty()) {
            return [
                'year' => $year,
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
                'status' => 'Không có dữ liệu khai báo cho năm ' . $year,
                'steps' => $returnDetailedSteps ? [] : null,
                'monthly_summaries' => [],
                'annual_dependent_deduction_months_details' => [] // Thêm trường này
            ];
        }

        $steps[] = [
            'step' => 1,
            'description' => 'Tổng hợp thu nhập và các khoản đã đóng/giảm trừ từ các khai báo hàng tháng trong năm:',
            'details' => [
                'Tổng lương Gross: ' . number_format($annualGrossSalary) . ' VNĐ',
                'Tổng thu nhập khác chịu thuế: ' . number_format($annualOtherTaxableIncome) . ' VNĐ',
                'Tổng thu nhập miễn thuế: ' . number_format($annualNonTaxableIncome) . ' VNĐ',
                'Tổng đóng góp từ thiện: ' . number_format($annualDeductionCharity) . ' VNĐ',
                'Tổng bảo hiểm bắt buộc đã đóng: ' . number_format($annualSocialInsuranceContribution) . ' VNĐ',
                'Tổng thuế TNCN đã tạm nộp/khấu trừ tại nguồn: ' . number_format($annualTaxDeductedAtSource) . ' VNĐ',
            ],
            'value' => null
        ];

        // 2. Tính tổng thu nhập chịu thuế cả năm
        $annualTotalTaxableIncome = ($annualGrossSalary + $annualOtherTaxableIncome) - $annualNonTaxableIncome;
        $steps[] = [
            'step' => 2,
            'description' => 'Tổng thu nhập chịu thuế cả năm (Tổng Gross + Tổng khác chịu thuế - Tổng miễn thuế):',
            'details' => [
                number_format($annualGrossSalary) . ' + ' . number_format($annualOtherTaxableIncome) . ' - ' . number_format($annualNonTaxableIncome) . ' = ' . number_format($annualTotalTaxableIncome) . ' VNĐ'
            ],
            'value' => $annualTotalTaxableIncome
        ];

        // 3. Tổng giảm trừ bản thân cả năm (luôn là 12 tháng)
        $personalDeductionAmountPerMonth = $this->getSystemConfigValue('personal_deduction_amount', Carbon::createFromDate($year, 1, 1)) ?? 11000000;
        $annualPersonalDeduction = $personalDeductionAmountPerMonth * 12;
        $steps[] = [
            'step' => 3,
            'description' => 'Tổng giảm trừ bản thân cả năm (Mức giảm trừ bản thân * 12 tháng):',
            'details' => [
                number_format($personalDeductionAmountPerMonth) . ' VNĐ/tháng * 12 tháng = ' . number_format($annualPersonalDeduction) . ' VNĐ'
            ],
            'value' => $annualPersonalDeduction
        ];

        // 4. Tổng giảm trừ người phụ thuộc cả năm (theo số tháng thực tế có người phụ thuộc hợp lệ)
        $dependentDeductionAmountPerMonth = $this->getSystemConfigValue('dependent_deduction_amount', Carbon::createFromDate($year, 1, 1)) ?? 4400000;
        $annualDependentDeduction = 0;
        $dependentMonthsDetails = []; // Chi tiết số tháng được giảm trừ của từng người phụ thuộc
        $totalDependentMonths = 0;

        // Lấy số tháng hợp lệ cho từng người phụ thuộc trong năm
        $dependentMonthsData = [];
        foreach ($user->dependents as $dependent) {
            $monthsCount = 0;
            for ($month = 1; $month <= 12; $month++) {
                $currentMonthDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
                if ($dependent->isValidForDeduction($currentMonthDate)) { // Sử dụng phương thức trong Dependent model
                    $monthsCount++;
                }
            }
            if ($monthsCount > 0) {
                $dependentMonthsData[] = [
                    'name' => $dependent->full_name,
                    'months' => $monthsCount,
                    'amount' => $monthsCount * $dependentDeductionAmountPerMonth
                ];
                $totalDependentMonths += $monthsCount;
                $annualDependentDeduction += ($monthsCount * $dependentDeductionAmountPerMonth);
            }
        }

        $dependentDetails = ['Mức giảm trừ người phụ thuộc: ' . number_format($dependentDeductionAmountPerMonth) . ' VNĐ/người/tháng'];
        foreach ($dependentMonthsData as $data) {
            $dependentDetails[] = $data['name'] . ': ' . $data['months'] . ' tháng (' . number_format($data['amount']) . ' VNĐ)';
        }

        $steps[] = [
            'step' => 4,
            'description' => 'Tổng giảm trừ người phụ thuộc cả năm (Tổng số tháng có người phụ thuộc hợp lệ * Mức giảm trừ/người/tháng):',
            'details' => $dependentDetails,
            'value' => $annualDependentDeduction
        ];

        // 5. Tổng các khoản giảm trừ cả năm
        $annualTotalDeduction = $annualPersonalDeduction
                               + $annualDependentDeduction
                               + $annualSocialInsuranceContribution
                               + $annualDeductionCharity;
        $steps[] = [
            'step' => 5,
            'description' => 'Tổng các khoản giảm trừ cả năm (Bản thân + Người phụ thuộc + BH Bắt buộc + Từ thiện):',
            'details' => [
                number_format($annualPersonalDeduction) . ' + ' . number_format($annualDependentDeduction) . ' + ' . number_format($annualSocialInsuranceContribution) . ' + ' . number_format($annualDeductionCharity) . ' = ' . number_format($annualTotalDeduction) . ' VNĐ'
            ],
            'value' => $annualTotalDeduction
        ];

        // 6. Tính thu nhập tính thuế cả năm
        $annualTaxableIncome = max(0, $annualTotalTaxableIncome - $annualTotalDeduction);
        $steps[] = [
            'step' => 6,
            'description' => 'Thu nhập tính thuế cả năm (Tổng thu nhập chịu thuế cả năm - Tổng giảm trừ cả năm):',
            'details' => [
                number_format($annualTotalTaxableIncome) . ' - ' . number_format($annualTotalDeduction) . ' = ' . number_format($annualTaxableIncome) . ' VNĐ (Nếu nhỏ hơn 0 thì là 0 VNĐ)'
            ],
            'value' => $annualTaxableIncome
        ];

        // 7. Áp dụng biểu thuế lũy tiến từng phần cho thu nhập tính thuế cả năm
        $annualPitAmount = $this->calculatePIT($annualTaxableIncome, Carbon::createFromDate($year, 12, 31)); // Áp dụng biểu thuế của năm đó
        $taxBrackets = $this->getTaxBrackets(Carbon::createFromDate($year, 12, 31)); // Lấy lại để hiển thị chi tiết
        $remainingTaxableIncomeForAnnualBreakdown = $annualTaxableIncome;
        $annualTaxCalculationBreakdown = [];

        foreach ($taxBrackets as $bracket) {
            if ($remainingTaxableIncomeForAnnualBreakdown <= 0) break;

            $minIncomeOfBracket = $bracket->min_income;
            $maxIncomeOfBracket = $bracket->max_income;
            $taxRate = $bracket->tax_rate;

            $upperBoundForCalculation = ($maxIncomeOfBracket !== null) ? $maxIncomeOfBracket : PHP_INT_MAX;
            $lowerBoundForCalculation = ($minIncomeOfBracket > 0) ? ($minIncomeOfBracket - 1) : 0;

            $incomeInThisBracket = min($remainingTaxableIncomeForAnnualBreakdown, $upperBoundForCalculation - $lowerBoundForCalculation);
            $incomeInThisBracket = max(0, $incomeInThisBracket);

            if ($incomeInThisBracket > 0) {
                $taxInBracket = $incomeInThisBracket * $taxRate;
                $annualTaxCalculationBreakdown[] = [
                    'bracket' => ($maxIncomeOfBracket === null ? 'Từ ' . number_format($minIncomeOfBracket) . ' VNĐ trở lên' : number_format($minIncomeOfBracket) . ' - ' . number_format($maxIncomeOfBracket) . ' VNĐ'),
                    'income_in_bracket' => number_format($incomeInThisBracket),
                    'rate' => ($taxRate * 100) . '%',
                    'tax_amount' => number_format($taxInBracket)
                ];
                $remainingTaxableIncomeForAnnualBreakdown -= $incomeInThisBracket;
            }
        }

        $steps[] = [
            'step' => 7,
            'description' => 'Thuế TNCN phải nộp cả năm (áp dụng biểu thuế lũy tiến từng phần cho thu nhập tính thuế cả năm):',
            'details' => $annualTaxCalculationBreakdown,
            'value' => $annualPitAmount
        ];

        // 8. Số thuế phải nộp thêm / được hoàn lại
        $taxToPayOrRefund = $annualPitAmount - $annualTaxDeductedAtSource;
        $status = '';
        if ($taxToPayOrRefund > 0) {
            $status = 'Phải nộp thêm';
        } elseif ($taxToPayOrRefund < 0) {
            $status = 'Được hoàn lại';
        } else {
            $status = 'Không phải nộp thêm / Không được hoàn lại';
        }
        $steps[] = [
            'step' => 8,
            'description' => 'Số thuế cần nộp thêm / được hoàn lại:',
            'details' => [
                number_format($annualPitAmount) . ' (Tổng thuế phải nộp cả năm) - ' . number_format($annualTaxDeductedAtSource) . ' (Tổng thuế đã tạm nộp) = ' . number_format($taxToPayOrRefund) . ' VNĐ',
                'Trạng thái: ' . $status
            ],
            'value' => $taxToPayOrRefund
        ];

        // Tóm tắt các khai báo hàng tháng để hiển thị trong báo cáo quyết toán
        $monthlySummaries = $declarations->map(function($declaration) {
            return [
                'month' => $declaration->declaration_month->format('m/Y'),
                'gross_salary' => $declaration->gross_salary,
                'other_taxable_income' => $declaration->other_taxable_income,
                'non_taxable_income' => $declaration->non_taxable_income,
                'deduction_charity' => $declaration->deduction_charity,
                'tax_deducted_at_source' => $declaration->tax_deducted_at_source,
                'social_insurance_contribution' => $declaration->social_insurance_contribution,
                'personal_deduction' => $declaration->personal_deduction,
                'dependent_deduction' => $declaration->dependent_deduction,
                'total_deduction' => $declaration->total_deduction,
                'taxable_income' => $declaration->taxable_income,
                'calculated_tax' => $declaration->calculated_tax,
                'net_salary' => $declaration->net_salary,
            ];
        })->toArray();

        $results = [
            'year' => $year,
            'annual_gross_salary' => $annualGrossSalary,
            'annual_other_taxable_income' => $annualOtherTaxableIncome,
            'annual_non_taxable_income' => $annualNonTaxableIncome,
            'annual_deduction_charity' => $annualDeductionCharity,
            'annual_total_taxable_income' => $annualTotalTaxableIncome,
            'annual_social_insurance_contribution' => $annualSocialInsuranceContribution,
            'annual_personal_deduction' => $annualPersonalDeduction,
            'annual_dependent_deduction' => $annualDependentDeduction,
            'annual_total_deduction' => $annualTotalDeduction,
            'annual_taxable_income' => $annualTaxableIncome,
            'annual_pit_amount' => $annualPitAmount,
            'annual_tax_deducted_at_source' => $annualTaxDeductedAtSource,
            'tax_to_pay_or_refund' => $taxToPayOrRefund,
            'status' => $status,
            'monthly_summaries' => $monthlySummaries,
            'annual_dependent_deduction_months_details' => array_column($dependentMonthsData, 'months', 'name') // Chi tiết số tháng của từng người phụ thuộc
        ];

        if ($returnDetailedSteps) {
            $results['steps'] = $steps;
        }

        return $results;
    }
}