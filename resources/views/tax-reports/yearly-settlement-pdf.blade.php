<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Báo cáo quyết toán thuế TNCN năm {{ $selectedYear }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #eee; }
        .footer { margin-top: 40px; text-align: center; font-style: italic; font-size: 11px; color: #777; }
    </style>
</head>
<body>
    <h2>BÁO CÁO QUYẾT TOÁN THUẾ TNCN</h2>
    <p><strong>Họ tên:</strong> {{ $user->name }}</p>
    <p><strong>Năm:</strong> {{ $selectedYear }}</p>
    <p><strong>Mã số thuế:</strong> {{ $user->tax_code ?? 'Chưa cập nhật' }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa cập nhật' }}</p>
    <p><strong>Số điện thoại:</strong> {{ $user->phone_number ?? 'Chưa cập nhật' }}</p>
    <p><strong>Ngày xuất:</strong> {{ $currentDate ?? date('d/m/Y') }}</p>

    <h4>Tổng hợp thu nhập theo từng nguồn</h4>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Nguồn thu nhập</th>
                <th>Tổng thu nhập gộp</th>
                <th>Tổng BHXH</th>
                <th>Tổng giảm trừ khác</th>
                <th>Tổng giảm trừ gia cảnh</th>
                <th>Tổng thu nhập tính thuế</th>
                <th>Tổng thuế đã nộp</th>
                <th>Tổng thu nhập thực nhận</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($incomeEntriesForSelectedYear->groupBy('income_source_id') as $sourceId => $entries)
                @php
                    $source = $entries->first()->incomeSource;
                    $gross = $entries->sum('gross_income');
                    $bhxh = $entries->sum('bhxh_deduction');
                    $other = $entries->sum('other_deductions');
                    $personal = $entries->sum('personal_deduction');
                    $taxable = $entries->sum('taxable_income');
                    $tax_paid = $entries->sum('tax_paid');
                    $net = $entries->sum('net_income');
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $source->name }}</td>
                    <td>{{ number_format($gross, 0, ',', '.') }}</td>
                    <td>{{ number_format($bhxh, 0, ',', '.') }}</td>
                    <td>{{ number_format($other, 0, ',', '.') }}</td>
                    <td>{{ number_format($personal, 0, ',', '.') }}</td>
                    <td>{{ number_format($taxable, 0, ',', '.') }}</td>
                    <td>{{ number_format($tax_paid, 0, ',', '.') }}</td>
                    <td>{{ number_format($net, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Tổng hợp quyết toán năm</h4>
    <table>
        <tr>
            <th>Tổng thu nhập gộp chịu thuế</th>
            <td>{{ number_format($yearlyTaxSettlement['total_gross_income'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tổng giảm trừ BHXH & khác</th>
            <td>{{ number_format($yearlyTaxSettlement['total_bhxh_deduction'] + $yearlyTaxSettlement['total_other_deductions'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tổng giảm trừ gia cảnh</th>
            <td>{{ number_format($yearlyTaxSettlement['total_personal_deductions'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tổng thu nhập tính thuế</th>
            <td>{{ number_format($yearlyTaxSettlement['total_taxable_income_yearly'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tổng thuế TNCN đã tạm nộp</th>
            <td>{{ number_format($yearlyTaxSettlement['total_tax_paid_provisional'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tổng thuế TNCN phải nộp cả năm</th>
            <td>{{ number_format($yearlyTaxSettlement['total_tax_required_yearly'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Kết quả quyết toán</th>
            <td>
                @if ($yearlyTaxSettlement['tax_to_pay_or_refund'] > 0)
                    Còn phải nộp: {{ number_format($yearlyTaxSettlement['tax_to_pay_or_refund'], 0, ',', '.') }} VNĐ
                @elseif ($yearlyTaxSettlement['tax_to_pay_or_refund'] < 0)
                    Được hoàn lại: {{ number_format(abs($yearlyTaxSettlement['tax_to_pay_or_refund']), 0, ',', '.') }} VNĐ
                @else
                    Không phải nộp thêm hoặc hoàn lại
                @endif
            </td>
        </tr>
    </table>

    <div class="footer">
        <p><em>(Báo cáo tự động từ hệ thống hỗ trợ quyết toán thuế TNCN. Số liệu dựa trên thông tin do người dùng cung cấp.)</em></p>
    </div>
</body>
</html>
