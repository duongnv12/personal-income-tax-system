<?php

namespace App\Http\Controllers;

use App\Models\Dependent;
use App\Models\IncomeEntry;
use App\Models\IncomeSource;
use App\Services\TaxCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IncomeEntryController extends Controller
{
    protected $taxService;

    public function __construct(TaxCalculationService $taxService)
    {
        $this->taxService = $taxService;
    }

    public function index(Request $request)
    {
        $query = Auth::user()->incomeEntries()->with('incomeSource')->orderBy('year', 'desc')->orderBy('month', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('gross_income', 'like', "%$search%")
                  ->orWhere('year', 'like', "%$search%")
                  ->orWhere('month', 'like', "%$search%")
                  ->orWhereHas('incomeSource', function ($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%$search%");
                  });
            });
        }

        $incomeEntries = $query->paginate(10);
        return view('income-entries.index', compact('incomeEntries'));
    }

    public function create()
    {
        $incomeSources = Auth::user()->incomeSources()->get();
        if ($incomeSources->isEmpty()) {
            return redirect()->route('income-sources.create')->with('info', 'Bạn cần tạo ít nhất một nguồn thu nhập trước khi thêm khoản thu nhập.');
        }

        $dependentCount = Dependent::where('user_id', Auth::id())
            ->where('status', 'active')
            ->count();
            
        return view('income-entries.create', compact('incomeSources', 'dependentCount'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'income_source_id' => [
                'required',
                Rule::exists('income_sources', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'entry_type' => 'required|in:monthly,yearly',
            'month' => [
                Rule::requiredIf($request->entry_type === 'monthly'),
                'nullable', 'integer', 'min:1', 'max:12'
            ],
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'calculation_direction' => 'required|in:gross_to_net,net_to_gross',
            'region' => 'required|in:1,2,3,4',
            'insurance_salary_type' => 'required|in:official,custom',
            'insurance_salary_custom' => 'nullable|string|min:0',
            'gross_income' => 'nullable|string|min:0',
            'net_income' => 'nullable|string|min:0',
            'dependents' => 'required|integer|min:0',
        ], [
            'income_source_id.required' => 'Nguồn thu nhập là bắt buộc.',
            'income_source_id.exists' => 'Nguồn thu nhập không hợp lệ.',
            'entry_type.required' => 'Loại nhập là bắt buộc.',
            'entry_type.in' => 'Loại nhập không hợp lệ.',
            'month.required_if' => 'Tháng là bắt buộc khi loại nhập là Hàng tháng.',
            'year.required' => 'Năm là bắt buộc.',
            'dependents.required' => 'Số người phụ thuộc là bắt buộc.',
            'dependents.integer' => 'Số người phụ thuộc phải là số nguyên.',
            'dependents.min' => 'Số người phụ thuộc không được là số âm.',
        ]);

        // Xử lý dữ liệu
        $validatedData['insurance_salary_custom'] = $validatedData['insurance_salary_custom'] ? (float)str_replace(',', '', $validatedData['insurance_salary_custom']) : null;
        $validatedData['gross_income'] = $validatedData['gross_income'] ? (float)str_replace(',', '', $validatedData['gross_income']) : null;
        $validatedData['net_income'] = $validatedData['net_income'] ? (float)str_replace(',', '', $validatedData['net_income']) : null;
        $validatedData['month'] = $request->entry_type === 'monthly' ? $validatedData['month'] : null;
        $validatedData['income_type'] = 'salary';

        $user = Auth::user();

        $result = app(\App\Services\TaxCalculationService::class)->calculateMonthlyTaxV2($validatedData);
        $error = isset($result['error']) && $result['error'] ? $result['error'] : null;

        if (!$error) {
            IncomeEntry::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'income_source_id' => $validatedData['income_source_id'],
                    'year' => $validatedData['year'],
                    'month' => $validatedData['month'],
                    'entry_type' => $validatedData['entry_type'],
                    'income_type' => 'salary',
                ],
                [
                    'gross_income' => $result['actual_gross_income'] ?? ($validatedData['gross_income'] ?? null),
                    'net_income' => $result['actual_net_income'] ?? ($validatedData['net_income'] ?? null),
                    'bhxh_deduction' => $result['actual_bhxh_deduction'] ?? null,
                    'tax_paid' => $result['actual_tax_paid'] ?? null,
                ]
            );
        }

        $incomeSources = $user->incomeSources()->get();
        
        return view('income-entries.create', [
            'result' => $result,
            'error' => $error,
            'incomeSources' => $incomeSources,
            'oldInput' => $validatedData,
        ]);
    }

    public function edit(IncomeEntry $incomeEntry)
    {
        if ($incomeEntry->user_id !== Auth::id()) {
            abort(403);
        }
        $incomeSources = Auth::user()->incomeSources()->get();
        return view('income-entries.edit', compact('incomeEntry', 'incomeSources'));
    }

    public function update(Request $request, IncomeEntry $incomeEntry)
    {
        if ($incomeEntry->user_id !== Auth::id()) {
            abort(403);
        }

        $validatedData = $request->validate([
            'income_source_id' => [
                'required',
                Rule::exists('income_sources', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'entry_type' => 'required|in:monthly,yearly',
            'month' => [
                Rule::requiredIf($request->entry_type === 'monthly'),
                'nullable', 'integer', 'min:1', 'max:12'
            ],
            'gross_income' => 'required|numeric|min:0',
            'bhxh_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ], [
            'income_source_id.required' => 'Nguồn thu nhập là bắt buộc.',
            'income_source_id.exists' => 'Nguồn thu nhập không hợp lệ.',
            'year.required' => 'Năm là bắt buộc.',
            'year.integer' => 'Năm phải là số nguyên.',
            'entry_type.required' => 'Loại nhập là bắt buộc.',
            'entry_type.in' => 'Loại nhập không hợp lệ.',
            'month.required_if' => 'Tháng là bắt buộc khi loại nhập là Hàng tháng.',
            'month.integer' => 'Tháng phải là số nguyên.',
            'gross_income.required' => 'Thu nhập Gross là bắt buộc.',
            'gross_income.numeric' => 'Thu nhập Gross phải là số.',
            'gross_income.min' => 'Thu nhập Gross không thể âm.',
        ]);

        $validatedData['month'] = $request->entry_type === 'monthly' ? $validatedData['month'] : null;

        $incomeSource = IncomeSource::findOrFail($validatedData['income_source_id']);
        $validatedData['income_type'] = $incomeSource->income_type; 

        $tempIncomeEntry = new IncomeEntry($validatedData);
        $calculatedData = $this->taxService->calculateMonthlyTax($tempIncomeEntry);

        $validatedData['bhxh_deduction'] = $calculatedData['actual_bhxh_deduction'];
        $validatedData['tax_paid'] = $calculatedData['actual_tax_paid'];
        $validatedData['net_income'] = $calculatedData['actual_net_income'];

        $incomeEntry->update($validatedData);

        return redirect()->route('income-entries.index')->with('success', 'Đã cập nhật khoản thu nhập thành công.');
    }

    public function destroy(IncomeEntry $incomeEntry)
    {
        if ($incomeEntry->user_id !== Auth::id()) {
            abort(403);
        }

        $incomeEntry->delete();

        return redirect()->route('income-entries.index')->with('success', 'Đã xóa khoản thu nhập thành công.');
    }
}