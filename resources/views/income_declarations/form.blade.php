@props(['incomeDeclaration' => null])

<div class="space-y-6">
    <div>
        <label for="declaration_month" class="block text-sm font-medium text-gray-700">Tháng khai báo (YYYY-MM)</label>
        <input type="month" name="declaration_month" id="declaration_month" required
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('declaration_month') border-red-500 @enderror"
               value="{{ old('declaration_month', $incomeDeclaration?->declaration_month?->format('Y-m') ?? Carbon\Carbon::now()->format('Y-m')) }}">
        @error('declaration_month')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="gross_salary" class="block text-sm font-medium text-gray-700">Lương Gross hàng tháng (VNĐ)</label>
        <input type="number" name="gross_salary" id="gross_salary" required min="0"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('gross_salary') border-red-500 @enderror"
               value="{{ old('gross_salary', $incomeDeclaration?->gross_salary ?? '') }}">
        @error('gross_salary')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="other_taxable_income" class="block text-sm font-medium text-gray-700">Thu nhập chịu thuế khác (nếu có)</label>
        <input type="number" name="other_taxable_income" id="other_taxable_income" min="0"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('other_taxable_income') border-red-500 @enderror"
               value="{{ old('other_taxable_income', $incomeDeclaration?->other_taxable_income ?? 0) }}">
        @error('other_taxable_income')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="non_taxable_income" class="block text-sm font-medium text-gray-700">Thu nhập miễn thuế (nếu có)</label>
        <input type="number" name="non_taxable_income" id="non_taxable_income" min="0"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('non_taxable_income') border-red-500 @enderror"
               value="{{ old('non_taxable_income', $incomeDeclaration?->non_taxable_income ?? 0) }}">
        @error('non_taxable_income')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="deduction_charity" class="block text-sm font-medium text-gray-700">Khoản đóng góp từ thiện, nhân đạo (nếu có)</label>
        <input type="number" name="deduction_charity" id="deduction_charity" min="0"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('deduction_charity') border-red-500 @enderror"
               value="{{ old('deduction_charity', $incomeDeclaration?->deduction_charity ?? 0) }}">
        @error('deduction_charity')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tax_deducted_at_source" class="block text-sm font-medium text-gray-700">Thuế đã khấu trừ tại nguồn (VNĐ)</label>
        <input type="number" name="tax_deducted_at_source" id="tax_deducted_at_source" min="0"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('tax_deducted_at_source') border-red-500 @enderror"
               value="{{ old('tax_deducted_at_source', $incomeDeclaration?->tax_deducted_at_source ?? 0) }}">
        @error('tax_deducted_at_source')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>