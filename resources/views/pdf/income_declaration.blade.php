<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Khai Báo Thu Nhập Tháng {{ $incomeDeclaration->declaration_month->format('m/Y') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Hoặc sử dụng font hỗ trợ tiếng Việt khác như 'times new roman' nếu có */
            font-size: 10pt;
            margin: 40px;
        }
        h1, h2, h3, h4 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .section-title {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 10px;
            color: #555;
        }
        .value {
            font-weight: bold;
            color: #000;
        }
        .note {
            font-size: 9pt;
            color: #777;
            margin-top: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Báo Cáo Chi Tiết Khai Báo Thu Nhập Cá Nhân</h1>
    <h2 class="text-center">Tháng {{ $incomeDeclaration->declaration_month->format('m/Y') }}</h2>
    <h3 class="text-center">Người dùng: {{ $incomeDeclaration->user->name }} ({{ $incomeDeclaration->user->email }})</h3>

    <hr>

    <div class="section">
        <div class="section-title">Thông tin chung:</div>
        <table>
            <tr>
                <td style="width:50%">Lương Gross:</td>
                <td class="value text-right">{{ number_format($incomeDeclaration->gross_salary, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td>Thu nhập khác chịu thuế:</td>
                <td class="value text-right">{{ number_format($incomeDeclaration->other_taxable_income, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td>Tổng thu nhập chịu thuế:</td>
                <td class="value text-right">{{ number_format($detailedCalculation['total_taxable_income'], 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td>**Thuế TNCN đã tính:**</td>
                <td class="value text-right" style="color:red">{{ number_format($incomeDeclaration->calculated_tax, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td>**Lương Net thực nhận:**</td>
                <td class="value text-right" style="color:green">{{ number_format($incomeDeclaration->net_salary, 0, ',', '.') }} VNĐ</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Các Bước Tính Thuế Chi Tiết:</div>
        @foreach ($detailedCalculation['steps'] as $step)
            <div style="margin-bottom: 15px;">
                <p style="font-weight: bold; color: #333;">Bước {{ $step['step'] }}: {{ $step['description'] }}</p>
                <ul style="margin-left: 20px; padding-left: 0; list-style-type: disc;">
                    @foreach ($step['details'] as $detail)
                        @if (is_array($detail))
                            <li>
                                <span style="font-weight: bold;">Bậc {{ $detail['bracket'] }}:</span> Thu nhập tính trong bậc: {{ $detail['income_in_bracket'] }} VNĐ, Tỷ lệ: {{ $detail['rate'] }} &rArr; Thuế: {{ $detail['tax_amount'] }} VNĐ
                            </li>
                        @else
                            <li>{{ $detail }}</li>
                        @endif
                    @endforeach
                </ul>
                @if ($step['value'] !== null)
                    <p style="font-weight: bold; margin-top: 5px;">Kết quả bước này: <span class="value">{{ number_format($step['value'], 0, ',', '.') }} VNĐ</span></p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="note">
        Báo cáo này được tạo tự động bởi hệ thống tính thuế TNCN vào ngày {{ date('d/m/Y H:i:s') }}.
    </div>
</body>
</html>