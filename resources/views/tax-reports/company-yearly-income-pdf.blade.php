{{-- filepath: resources/views/tax-reports/company-yearly-income-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Báo cáo thu nhập tính thuế theo công ty</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>BÁO CÁO THU NHẬP TÍNH THUẾ THEO CÔNG TY</h2>
    <p><strong>Họ tên:</strong> {{ $user->name }}</p>
    <p><strong>Năm:</strong> {{ $selectedYear }}</p>
    <p><strong>Công ty:</strong> {{ $company->name ?? '' }}</p>
    <p><strong>Ngày xuất:</strong> {{ $currentDate }}</p>

    <h4>Chi tiết các khoản thu nhập</h4>
    <table>
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Loại thu nhập</th>
                <th>Thu nhập gộp</th>
                <th>BHXH (nếu có)</th>
                <th>Giảm trừ khác</th>
                <th>Thuế đã nộp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incomeEntries as $entry)
                <tr>
                    <td>{{ $entry->month }}</td>
                    <td>{{ $entry->income_type }}</td>
                    <td>{{ number_format($entry->gross_income) }}</td>
                    <td>{{ number_format($entry->bhxh_deduction ?? 0) }}</td>
                    <td>{{ number_format($entry->other_deductions ?? 0) }}</td>
                    <td>{{ number_format($entry->tax_paid ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Tổng hợp</h4>
    <table>
        <tr>
            <th>Tổng thu nhập gộp</th>
            <td>{{ number_format($taxableSummary['total_gross_income']) }}</td>
        </tr>
        <tr>
            <th>Tổng BHXH</th>
            <td>{{ number_format($taxableSummary['total_bhxh_deduction']) }}</td>
        </tr>
        <tr>
            <th>Tổng giảm trừ khác</th>
            <td>{{ number_format($taxableSummary['total_other_deductions']) }}</td>
        </tr>
        <tr>
            <th>Tổng thuế đã nộp</th>
            <td>{{ number_format($taxableSummary['total_tax_paid']) }}</td>
        </tr>
    </table>
</body>
</html>