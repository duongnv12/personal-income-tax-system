<?php

namespace App\Exports;

use App\Models\IncomeEntry;
use App\Models\User;
use App\Services\TaxCalculationService; // Import service
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class YearlyTaxSettlementExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $user;
    protected $year;
    protected $taxService;
    protected $yearlyTaxSettlement;
    protected $incomeEntriesForSelectedYear;

    public function __construct(User $user, int $year, TaxCalculationService $taxService)
    {
        $this->user = $user;
        $this->year = $year;
        $this->taxService = $taxService;

        // Tính toán dữ liệu một lần
        $this->yearlyTaxSettlement = $this->taxService->calculateYearlyTaxSettlement($this->user, $this->year);
        $this->incomeEntriesForSelectedYear = $this->user->incomeEntries()
                                                         ->where('year', $this->year)
                                                         ->with('incomeSource')
                                                         ->orderBy('month')
                                                         ->get();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Thêm dữ liệu tóm tắt vào collection
        $summary = collect([
            ['TỔNG HỢP QUYẾT TOÁN THUẾ TNCN NĂM ' . $this->year],
            [''], // Hàng trống
            ['Thông tin Người nộp thuế:'],
            ['Họ và tên:', $this->user->name],
            ['Mã số thuế:', $this->user->tax_code ?? ''],
            ['Email:', $this->user->email],
            ['Địa chỉ:', $this->user->address ?? ''],
            [''],
            ['Chỉ tiêu', 'Số tiền (VNĐ)'],
            ['Tổng thu nhập Gross chịu thuế', $this->yearlyTaxSettlement['total_gross_income']],
            ['Tổng giảm trừ Bảo hiểm và các khoản khác', $this->yearlyTaxSettlement['total_bhxh_deduction'] + $this->yearlyTaxSettlement['total_other_deductions']],
            ['Tổng giảm trừ Gia cảnh', $this->yearlyTaxSettlement['total_personal_deductions']],
            ['Thu nhập tính thuế cả năm', $this->yearlyTaxSettlement['total_taxable_income_yearly']],
            ['Tổng thuế TNCN đã tạm nộp trong năm', $this->yearlyTaxSettlement['total_tax_paid_provisional']],
            ['Tổng thuế TNCN phải nộp cả năm', $this->yearlyTaxSettlement['total_tax_required_yearly']],
            [''],
            ['Kết quả quyết toán:'],
            [
                $this->yearlyTaxSettlement['tax_to_pay_or_refund'] > 0 ? 'Số thuế còn phải nộp thêm' : ($this->yearlyTaxSettlement['tax_to_pay_or_refund'] < 0 ? 'Số thuế được hoàn lại' : 'Không có số thuế phải nộp thêm hoặc được hoàn lại'),
                $this->yearlyTaxSettlement['tax_to_pay_or_refund']
            ],
            [''],
            ['CHI TIẾT CÁC KHOẢN THU NHẬP TRONG NĂM ' . $this->year],
        ]);

        // Gộp dữ liệu tóm tắt với dữ liệu chi tiết
        return $summary->merge($this->incomeEntriesForSelectedYear);
    }

    public function headings(): array
    {
        // Tiêu đề cho phần chi tiết thu nhập. Sẽ được áp dụng sau phần tóm tắt.
        return [
            'Tháng',
            'Nguồn thu nhập',
            'Loại nhập',
            'Gross (VNĐ)',
            'BHXH đã khấu trừ (VNĐ)',
            'Các khoản giảm khác (VNĐ)',
            'Thuế tạm nộp (VNĐ)',
            'Net ước tính (VNĐ)',
        ];
    }

    public function map($row): array
    {
        // Kiểm tra nếu $row là instance của IncomeEntry (dữ liệu chi tiết)
        if ($row instanceof IncomeEntry) {
            return [
                $row->entry_type === 'monthly' ? $row->month : 'Cả năm',
                $row->incomeSource->name,
                $row->entry_type === 'monthly' ? 'Hàng tháng' : 'Hàng năm',
                $row->gross_income,
                $row->bhxh_deduction ?? 0,
                $row->other_deductions ?? 0,
                $row->tax_paid ?? 0,
                $row->net_income ?? 0,
            ];
        }
        // Nếu không, đây là dữ liệu tóm tắt hoặc hàng trống
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        // Styles cho toàn bộ sheet hoặc các vùng cụ thể

        // Tiêu đề chính
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FF000000']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFCCEEFF']], // Light blue background
        ]);

        // Tiêu đề 'Thông tin Người nộp thuế'
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE6F7FF']],
        ]);
        $sheet->mergeCells('A3:H3');

        // Styles cho thông tin người nộp thuế (A4:A7)
        $sheet->getStyle('A4:A7')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Tiêu đề 'Chỉ tiêu', 'Số tiền (VNĐ)'
        $sheet->getStyle('A9:B9')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF0056B3']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9EDF7']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Styles cho bảng tóm tắt (A10:B15)
        $sheet->getStyle('A10:B15')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getStyle('B10:B15')->applyFromArray([
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
            'numberFormat' => ['formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1],
        ]);
        $sheet->getStyle('A13:A15')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Kết quả quyết toán
        $sheet->getStyle('A17')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE6F7FF']],
        ]);
        $sheet->mergeCells('A17:H17');

        $sheet->getStyle('A18:B18')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getStyle('B18')->applyFromArray([
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
            'numberFormat' => ['formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1],
        ]);
        if ($this->yearlyTaxSettlement['tax_to_pay_or_refund'] > 0) {
            $sheet->getStyle('B18')->getFont()->getColor()->setARGB('FFFF0000'); // Red
        } elseif ($this->yearlyTaxSettlement['tax_to_pay_or_refund'] < 0) {
            $sheet->getStyle('B18')->getFont()->getColor()->setARGB('FF008000'); // Green
        }


        // Tiêu đề 'CHI TIẾT CÁC KHOẢN THU NHẬP'
        $headerRowStart = 21; // Dòng bắt đầu của tiêu đề chi tiết. Cần tính toán dựa trên số dòng tóm tắt
        // Để chính xác, bạn cần đếm số dòng summary. Tạm thời dùng 21
        $sheet->getStyle('A' . $headerRowStart)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE6F7FF']],
        ]);
        $sheet->mergeCells('A' . $headerRowStart . ':H' . $headerRowStart);

        // Styles cho tiêu đề bảng chi tiết (ngay sau dòng tóm tắt)
        $headingsRow = $headerRowStart + 1;
        $sheet->getStyle('A' . $headingsRow . ':H' . $headingsRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FF555555']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8F9FA']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Styles cho dữ liệu bảng chi tiết
        $dataRowStart = $headingsRow + 1;
        $dataRowEnd = $dataRowStart + $this->incomeEntriesForSelectedYear->count() - 1;
        if ($this->incomeEntriesForSelectedYear->count() > 0) {
            $sheet->getStyle('A' . $dataRowStart . ':H' . $dataRowEnd)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
            // Căn phải và định dạng số cho các cột số
            $sheet->getStyle('D' . $dataRowStart . ':H' . $dataRowEnd)->applyFromArray([
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                'numberFormat' => ['formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1],
            ]);
        }
    }
}