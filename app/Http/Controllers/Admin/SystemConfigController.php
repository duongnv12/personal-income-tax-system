<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig; // Đảm bảo đã import
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Đảm bảo đã import

class SystemConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách các key hiện có để hiển thị trong form thêm nhanh
        $existingKeys = SystemConfig::select('key')->distinct()->pluck('key');

        // Lấy tất cả các cấu hình duy nhất theo key với effective_date mới nhất
        // CẬP NHẬT DÒNG NÀY: THÊM 'id' VÀO SELECT
        $latestConfigs = SystemConfig::select('id', 'key', 'value', 'description', 'effective_date')
                                     ->whereIn('id', function($query){
                                         $query->selectRaw('MAX(id)')
                                               ->from('system_configs')
                                               ->groupBy('key');
                                     })
                                     ->orderBy('key')
                                     ->get();

        // Lấy tất cả các bản ghi cấu hình để hiển thị bảng chi tiết (nếu có)
        // Đây là biến $systemConfigs bạn đã có, không cần thay đổi
        $systemConfigs = SystemConfig::orderBy('key')->orderBy('effective_date', 'desc')->get();

        return view('admin.system_configs.index', compact('systemConfigs', 'latestConfigs', 'existingKeys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TRUYỀN MỘT INSTANCE MỚI CỦA SYSTEMCONFIG ĐỂ TRÁNH LỖI "attempt to read property on null"
        return view('admin.system_configs.create', ['systemConfig' => new SystemConfig()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:1000'],
            'effective_date' => ['required', 'date'],
        ]);

        SystemConfig::create($validated);

        return redirect()->route('admin.system_configs.index')->with('success', 'Cấu hình hệ thống đã được thêm thành công.');
    }

    /**
     * Store a newly created resource in storage (quick add from index).
     */
    public function storeQuick(Request $request)
    {
        $validated = $request->validate([
            'quick_key' => ['required', 'string', 'max:255'],
            'quick_value' => ['required', 'numeric'],
            'quick_description' => ['nullable', 'string', 'max:1000'],
            'quick_effective_date' => ['required', 'date'],
        ]);

        SystemConfig::create([
            'key' => $validated['quick_key'],
            'value' => $validated['quick_value'],
            'description' => $validated['quick_description'],
            'effective_date' => $validated['quick_effective_date'],
        ]);

        return redirect()->route('admin.system_configs.index')->with('success', 'Cấu hình đã được thêm nhanh thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemConfig $systemConfig)
    {
        // Laravel tự động tìm SystemConfig dựa trên ID.
        // Nếu không tìm thấy, nó sẽ tự động ném 404.
        return view('admin.system_configs.edit', compact('systemConfig'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SystemConfig $systemConfig)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', Rule::unique('system_configs')->ignore($systemConfig->id)], // Đảm bảo key vẫn là duy nhất nhưng bỏ qua bản ghi hiện tại
            'value' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:1000'],
            'effective_date' => ['required', 'date'],
        ]);

        $systemConfig->update($validated);

        return redirect()->route('admin.system_configs.index')->with('success', 'Cấu hình hệ thống đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemConfig $systemConfig)
    {
        $systemConfig->delete();
        return redirect()->route('admin.system_configs.index')->with('success', 'Cấu hình hệ thống đã được xóa thành công.');
    }
}