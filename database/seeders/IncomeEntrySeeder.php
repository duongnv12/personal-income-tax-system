<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomeEntry;
use App\Models\User;
use App\Models\IncomeSource;
use App\Services\TaxCalculationService; // Import service để tính toán

class IncomeEntrySeeder extends Seeder
{
    protected $taxService;

    public function __construct(TaxCalculationService $taxService)
    {
        $this->taxService = $taxService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();

        if ($user) {
            // Xóa các khoản thu nhập cũ của user này để tránh trùng lặp
            $user->incomeEntries()->delete();

            $sourceLuongCtyA = IncomeSource::where('user_id', $user->id)->where('name', 'Lương Công ty A')->first();
            $sourceLamThem = IncomeSource::where('user_id', $user->id)->where('name', 'Thu nhập làm thêm')->first();
            $sourceChoThueNha = IncomeSource::where('user_id', $user->id)->where('name', 'Cho thuê nhà')->first();

            $currentYear = date('Y');
            $previousYear = $currentYear - 1;

            if ($sourceLuongCtyA && $sourceLamThem && $sourceChoThueNha) {
                // --- Dữ liệu cho năm hiện tại ($currentYear) ---
                $grossIncome1 = 30000000;
                $grossIncome2 = 5000000;
                $grossIncome3 = 8000000;

                // Tạo các khoản thu nhập hàng tháng cho năm hiện tại
                for ($month = 1; $month <= 12; $month++) {
                    $entryData1 = [
                        'user_id' => $user->id,
                        'income_source_id' => $sourceLuongCtyA->id,
                        'gross_income' => $grossIncome1,
                        'entry_type' => 'monthly',
                        'month' => $month,
                        'year' => $currentYear,
                        'other_deductions' => 0,
                    ];
                    // Tính toán thuế, BHXH, net income trước khi lưu
                    $tempEntry1 = new IncomeEntry($entryData1);
                    $tempEntry1->user_id = $user->id; // Cần set user_id để service tính đúng người phụ thuộc
                    $calcResult1 = $this->taxService->calculateMonthlyTax($tempEntry1);
                    $entryData1['bhxh_deduction'] = $calcResult1['actual_bhxh_deduction'];
                    $entryData1['tax_paid'] = $calcResult1['actual_tax_paid'];
                    $entryData1['net_income'] = $calcResult1['actual_net_income'];
                    IncomeEntry::create($entryData1);


                    // Thêm một số tháng có thu nhập làm thêm
                    if (in_array($month, [3, 6, 9, 12])) { // Ví dụ: Có thêm thu nhập vào các tháng quý
                        $entryData2 = [
                            'user_id' => $user->id,
                            'income_source_id' => $sourceLamThem->id,
                            'gross_income' => $grossIncome2,
                            'entry_type' => 'monthly',
                            'month' => $month,
                            'year' => $currentYear,
                            'other_deductions' => 0,
                        ];
                        // Không tính thuế cho thu nhập làm thêm dưới 10tr (tùy vào quy định cụ thể)
                        // Giả định đây là thu nhập đã khấu trừ 10% tại nguồn nếu không có hợp đồng dài hạn
                        // Để đơn giản, ta sẽ lưu gross và coi như đã nộp thuế, không tính lại BHXH
                        $entryData2['bhxh_deduction'] = 0;
                        $entryData2['tax_paid'] = $grossIncome2 * 0.1; // 10% thuế
                        $entryData2['net_income'] = $grossIncome2 - $entryData2['tax_paid'];
                        IncomeEntry::create($entryData2);
                    }
                }

                // Thêm một khoản thu nhập "cả năm" (ví dụ: cho thuê nhà)
                IncomeEntry::create([
                    'user_id' => $user->id,
                    'income_source_id' => $sourceChoThueNha->id,
                    'gross_income' => $grossIncome3 * 12, // Tổng thu nhập cả năm
                    'entry_type' => 'yearly',
                    'month' => null, // Không có tháng cụ thể
                    'year' => $currentYear,
                    'other_deductions' => 0,
                    'bhxh_deduction' => 0, // Không áp dụng BHXH
                    'tax_paid' => ($grossIncome3 * 12) * 0.05, // Ví dụ: 5% thuế GTGT và TNCN cho thuê nhà
                    'net_income' => ($grossIncome3 * 12) * 0.95,
                ]);

                // --- Dữ liệu cho năm trước đó ($previousYear) ---
                // Tạo một số khoản thu nhập cho năm trước để kiểm tra báo cáo quá khứ
                for ($month = 1; $month <= 6; $month++) { // Chỉ 6 tháng đầu năm trước
                    $entryDataPrevYear = [
                        'user_id' => $user->id,
                        'income_source_id' => $sourceLuongCtyA->id,
                        'gross_income' => 25000000,
                        'entry_type' => 'monthly',
                        'month' => $month,
                        'year' => $previousYear,
                        'other_deductions' => 0,
                    ];
                    $tempEntryPrev = new IncomeEntry($entryDataPrevYear);
                    $tempEntryPrev->user_id = $user->id; // Cần set user_id để service tính đúng người phụ thuộc
                    $calcResultPrev = $this->taxService->calculateMonthlyTax($tempEntryPrev);
                    $entryDataPrevYear['bhxh_deduction'] = $calcResultPrev['actual_bhxh_deduction'];
                    $entryDataPrevYear['tax_paid'] = $calcResultPrev['actual_tax_paid'];
                    $entryDataPrevYear['net_income'] = $calcResultPrev['actual_net_income'];
                    IncomeEntry::create($entryDataPrevYear);
                }
            } else {
                $this->command->warn('Không tìm thấy nguồn thu nhập cho người dùng test. Hãy chạy IncomeSourceSeeder trước.');
            }
        } else {
            $this->command->warn('Không tìm thấy người dùng test. Hãy đảm bảo UserSeeder đã chạy và tạo user@example.com.');
        }
    }
}