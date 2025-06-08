<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TaxParameter;
use App\Models\TaxBracket;
use App\Models\IncomeEntry;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalIncomeEntries = IncomeEntry::count();
        $totalTaxParameters = TaxParameter::count();
        $totalTaxBrackets = TaxBracket::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalIncomeEntries',
            'totalTaxParameters',
            'totalTaxBrackets'
        ));
    }
}