<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\DependentController;
use App\Http\Controllers\IncomeSourceController;
use App\Http\Controllers\IncomeEntryController;
use App\Http\Controllers\TaxReportController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TaxParameterController;
use App\Http\Controllers\Admin\TaxBracketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::resource('dependents', DependentController::class)->except(['show']); 
    Route::resource('income-sources', IncomeSourceController::class)->except(['show']);
    Route::resource('income-entries', IncomeEntryController::class)->except(['show']);
    Route::get('/tax-reports/{year?}', [TaxReportController::class, 'showYearlySettlement'])->name('tax.yearly_settlement');
    Route::get('/tax-reports/{year}/export-pdf', [TaxReportController::class, 'exportYearlySettlementPdf'])->name('tax.yearly_settlement.export_pdf');
    Route::get('/tax-reports/{year}/export-excel', [TaxReportController::class, 'exportYearlySettlementExcel'])->name('tax.yearly_settlement.export_excel'); // Route mới
    Route::get('tax-reports/{year}/company/{companyId}/pdf', [TaxReportController::class, 'exportYearlyCompanyIncomePdf'])->name('tax-reports.company-income-pdf');

    // Route cho quản lý Tham số Thuế
    Route::get('/tax-parameters', [TaxParameterController::class, 'index'])->name('tax_parameters.index');
    Route::patch('/tax-parameters', [TaxParameterController::class, 'update'])->name('tax_parameters.update');

    Route::resource('tax-brackets', TaxBracketController::class)->except(['show']);
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class); 

    Route::resource('tax-parameters', TaxParameterController::class)->except(['create', 'store']); // Chỉ cho xem, sửa, xóa

    Route::resource('tax-brackets', TaxBracketController::class);

    Route::get('/tax-reports/{year}/source/{source}/details', [App\Http\Controllers\TaxReportController::class, 'getSourceDetailsJson'])->name('tax-reports.source-details');

});

Route::get('/income-entries/create', [IncomeEntryController::class, 'create'])->name('income-entries.create');
Route::post('/income-entries', [IncomeEntryController::class, 'store'])->name('income-entries.store');

require __DIR__.'/auth.php';
