<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quyết Toán Thuế TNCN Năm {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Hoặc font hỗ trợ tiếng Việt khác */
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
        .green-text { color: green; }
        .red-text { color: red; }
    </style>
</head>
<body>
    <h1>Báo Cáo Quyết Toán Thuế Thu Nhập Cá Nhân</h1>
    <h2 class="text-center">Năm {{ $year }}</h2>
    <h3 class="text-center">Người dùng: {{ Auth::user()->name }} ({{ Auth::user()->email }})</h3>

    <hr>

    @if (empty($settlementResults['monthly_summaries']))
        <p class="text-center">Không có dữ liệu khai báo thu nhập cho năm {{ $year }} để quyết toán.</p>
    @else
        <div class="section">
            <div class="section-title">Tóm tắt Quyết toán Thuế TNCN Cả năm:</div>
            <table>
                <tr>
                    <td style="width:50%">Tổng lương Gross cả năm:</td>
                    <td class="value text-right">{{ number_format($settlementResults['annual_gross_salary'], 0, ',', '.') }} VNĐ</td>
                </tr>
                <tr>
                    <td>Tổng bảo hiểm bắt buộc đã đóng cả năm:</td>
                    <td class="value text-right">{{ number_format($settlementResults['annual_social_insurance_contribution'], 0, ',', '.') }} VNĐ</td>
                </tr>
                <tr>
                    <td>Tổng giảm trừ bản thân cả năm:</td>
                    <td class="value text-right">{{ number_format($settlementResults['annual_personal_deduction'], 0, ',', '.') }} VNĐ</td>
                </tr>
                <tr>
                    <td>Tổng giảm trừ người phụ thuộc cả năm:</td>
                    <td class="value text-right">{{ number_format($settlementResults['annual_dependent_deduction'], 0, ',', '.') }} VNĐ</td>
                </tr>
                <tr>
                    <td>Tổng thu nhập tính thuế cả năm:</td>
                    <td class="value text-right">{{ number_format($settlementResults['annual_taxable_income'], 0, ',', '.') }} VNĐ</td>
                </tr>
                <tr>
                    <td>**Tổng thuế TNCN phải nộp cả năm:**</td>
                    <td class="value text-right red-text">{{ number_format($settlementResults['annual_pit_amount'], 0, ',', '.') }} VNĐ</td>
                </tr>
                <tr>
                    <td>**Tổng thuế TNCN đã tạm nộp/khấu trừ:**</td>
                    <td class="value text-right">{{ number_format($settlementResults['annual_tax_deducted_at_source'], 0, ',', '.') }} VNĐ</td>
                </tr>
                <tr>
                    <td>**Số thuế cần nộp thêm / được hoàn lại:**</td>
                    <td class="value text-right {{ $settlementResults['tax_to_pay_or_refund'] > 0 ? 'red-text' : ($settlementResults['tax_to_pay_or_refund'] < 0 ? 'green-text' : '') }}">
                        {{ number_format(abs($settlementResults['tax_to_pay_or_refund']), 0, ',', '.') }} VNĐ
                    </td>
                </tr>
                <tr>
                    <td>Trạng thái:</td>
                    <td class="value text-right">{{ $settlementResults['status'] }}</td>
                </tr>
            </table>
        </div>

        <div class="section" style="page-break-before: always;">
            <div class="section-title">Các Bước Tính Thuế Chi Tiết cho Quyết toán Năm:</div>
            @foreach ($settlementResults['steps'] as $step)
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

        @if (!empty($settlementResults['monthly_summaries']))
            <div class="section" style="page-break-before: always;">
                <div class="section-title">Tổng hợp Thu nhập & Thuế theo Tháng:</div>
                <table>
                    <thead>
                        <tr>
                            <th>Tháng</th>
                            <th>Lương Gross</th>
                            <th>BH Bắt buộc</th>
                            <th>Thuế TNCN</th>
                            <th>Lương Net</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($settlementResults['monthly_summaries'] as $summary)
                            <tr>
                                <td>{{ $summary['month'] }}</td>
                                <td class="text-right">{{ number_format($summary['gross_salary'], 0, ',', '.') }} VNĐ</td>
                                <td class="text-right">{{ number_format($summary['social_insurance_contribution'], 0, ',', '.') }} VNĐ</td>
                                <td class="text-right">{{ number_format($summary['calculated_tax'], 0, ',', '.') }} VNĐ</td>
                                <td class="text-right">{{ number_format($summary['net_salary'], 0, ',', '.') }} VNĐ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    <div class="note">
        Báo cáo này được tạo tự động bởi hệ thống tính thuế TNCN vào ngày {{ date('d/m/Y H:i:s') }}.
    </div>
</body>
</html>