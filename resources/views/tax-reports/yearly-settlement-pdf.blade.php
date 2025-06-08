<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tờ khai Quyết toán thuế TNCN năm {{ $selectedYear }}</title>
    <style>
        /* Import font nếu cần, DomPDF cần font đã được nhúng hoặc cài đặt */
        /* Ví dụ nếu bạn muốn dùng font Arial, bạn cần nhúng nó hoặc cấu hình trong dompdf_font_family_cache.php */
        /* @font-face {
            font-family: 'Arial Unicode MS';
            src: url('{{ public_path('fonts/ARIALUNI.TTF') }}') format('truetype');
        } */

        body {
            font-family: 'DejaVu Sans', sans-serif; /* Font hỗ trợ tiếng Việt cho DomPDF */
            margin: 40px;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        h1, h2, h3 {
            text-align: center;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        h1 {
            font-size: 20pt;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        h3 {
            font-size: 16pt;
            margin-top: 0;
            color: #555;
        }
        h2 {
            font-size: 14pt;
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff; /* Accent blue */
            padding-bottom: 8px;
            text-align: left;
            color: #007bff;
        }
        p {
            margin-bottom: 5px;
        }
        .info-block p strong {
            display: inline-block;
            width: 120px; /* Align labels */
            text-align: right;
            margin-right: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 10px 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            font-size: 9pt;
        }
        td {
            font-size: 10pt;
        }
        .summary-box {
            background-color: #e6f7ff; /* light blue */
            border: 1px solid #91d5ff; /* darker blue */
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
        }
        .summary-box p {
            margin: 8px 0;
            font-size: 11pt;
        }
        .summary-box .highlight {
            font-weight: bold;
            font-size: 1.2em;
            color: #0056b3; /* dark blue */
        }
        .summary-box .final-result {
            font-size: 1.3em;
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #91d5ff;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-style: italic;
            font-size: 9pt;
            color: #777;
        }
        .signatures {
            margin-top: 60px;
            overflow: hidden; /* Clearfix */
        }
        .signature-block {
            width: 48%; /* Adjust for spacing */
            float: left;
            text-align: center;
        }
        .signature-block:last-child {
            float: right;
        }
        .signature-block p {
            margin-top: 5px;
            margin-bottom: 0;
            font-weight: bold;
        }
        .signature-placeholder {
            margin-top: 80px; /* Space for actual signature */
            font-style: italic;
            border-bottom: 1px dotted #555; /* Line for signature */
            display: inline-block;
            padding: 0 20px;
        }
        .date-location {
            text-align: right;
            font-style: italic;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="date-location">
        Sông Công, ngày {{ date('d') }} tháng {{ date('m') }} năm {{ date('Y') }}
    </div>

    <h1>TỜ KHAI QUYẾT TOÁN THUẾ THU NHẬP CÁ NHÂN</h1>
    <h3>(Dành cho cá nhân có thu nhập từ tiền lương, tiền công tự quyết toán)</h3>
    <h3 style="margin-bottom: 20px;">Năm {{ $selectedYear }}</h3>

    <h2>I. Thông tin Người nộp thuế</h2>
    <div class="info-block">
        <p><strong>Họ và tên:</strong> {{ $user->name }}</p>
        <p><strong>Mã số thuế:</strong> {{ $user->tax_code ?? 'Chưa cập nhật' }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa cập nhật' }}</p>
        <p><strong>Số điện thoại:</strong> {{ $user->phone_number ?? 'Chưa cập nhật' }}</p>
    </div>

    <h2>II. Tổng hợp Quyết toán thuế TNCN trong năm</h2>
    <div class="summary-box">
        <p>Tổng thu nhập Gross chịu thuế: <span class="highlight text-right">{{ number_format($yearlyTaxSettlement['total_gross_income'], 0, ',', '.') }} VNĐ</span></p>
        <p>Tổng giảm trừ Bảo hiểm và các khoản khác: <span class="highlight text-right">{{ number_format($yearlyTaxSettlement['total_bhxh_deduction'] + $yearlyTaxSettlement['total_other_deductions'], 0, ',', '.') }} VNĐ</span></p>
        <p>Tổng giảm trừ Gia cảnh (bản thân và người phụ thuộc): <span class="highlight text-right">{{ number_format($yearlyTaxSettlement['total_personal_deductions'], 0, ',', '.') }} VNĐ</span></p>
        <p><strong>Tổng thu nhập tính thuế cả năm: <span class="highlight text-right">{{ number_format($yearlyTaxSettlement['total_taxable_income_yearly'], 0, ',', '.') }} VNĐ</span></strong></p>
        <p>Tổng thuế TNCN đã tạm nộp trong năm: <span class="highlight text-right">{{ number_format($yearlyTaxSettlement['total_tax_paid_provisional'], 0, ',', '.') }} VNĐ</span></p>
        <p><strong>Tổng thuế TNCN phải nộp cả năm: <span class="highlight text-right">{{ number_format($yearlyTaxSettlement['total_tax_required_yearly'], 0, ',', '.') }} VNĐ</span></strong></p>

        <div class="final-result">
            @if ($yearlyTaxSettlement['tax_to_pay_or_refund'] > 0)
                <p class="text-orange-800"><strong>Số thuế còn phải nộp thêm: {{ number_format($yearlyTaxSettlement['tax_to_pay_or_refund'], 0, ',', '.') }} VNĐ</strong></p>
            @elseif ($yearlyTaxSettlement['tax_to_pay_or_refund'] < 0)
                <p class="text-teal-800"><strong>Số thuế được hoàn lại: {{ number_format(abs($yearlyTaxSettlement['tax_to_pay_or_refund']), 0, ',', '.') }} VNĐ</strong></p>
            @else
                <p class="text-gray-800"><strong>Bạn không có số thuế phải nộp thêm hoặc được hoàn lại trong năm {{ $selectedYear }}.</strong></p>
            @endif
        </div>
    </div>

    <h2>III. Chi tiết các khoản thu nhập trong năm {{ $selectedYear }}</h2>
    @if ($incomeEntriesForSelectedYear->isEmpty())
        <p class="text-center">Không có dữ liệu thu nhập cho năm {{ $selectedYear }}.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Tháng</th>
                    <th>Nguồn thu nhập</th>
                    <th>Loại nhập</th>
                    <th class="text-right">Gross (VNĐ)</th>
                    <th class="text-right">BHXH đã khấu trừ (VNĐ)</th>
                    <th class="text-right">Các khoản giảm khác (VNĐ)</th>
                    <th class="text-right">Thuế tạm nộp (VNĐ)</th>
                    <th class="text-right">Net ước tính (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incomeEntriesForSelectedYear as $entry)
                    <tr>
                        <td>{{ $entry->entry_type === 'monthly' ? $entry->month : 'Cả năm' }}</td>
                        <td>{{ $entry->incomeSource->name }}</td>
                        <td>{{ $entry->entry_type === 'monthly' ? 'Hàng tháng' : 'Hàng năm' }}</td>
                        <td class="text-right">{{ number_format($entry->gross_income, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($entry->bhxh_deduction ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($entry->other_deductions ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($entry->tax_paid ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($entry->net_income ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="signatures">
        <div class="signature-block">
            <p><strong>Người nộp thuế</strong></p>
            <p>(Ký, ghi rõ họ tên)</p>
            <p class="signature-placeholder"></p>
            <p>{{ $user->name }}</p>
        </div>
        <div class="signature-block">
            <p><strong>Người lập biểu</strong></p>
            <p>(Ký, ghi rõ họ tên)</p>
            <p class="signature-placeholder"></p>
            <p>Hệ thống hỗ trợ tự động</p>
        </div>
    </div>

    <div class="footer">
        <p><em>(Đây là báo cáo tự động được tạo bởi hệ thống hỗ trợ quyết toán thuế TNCN. Mọi số liệu được tính toán dựa trên thông tin do người dùng cung cấp và các quy định hiện hành.)</em></p>
    </div>
</body>
</html>