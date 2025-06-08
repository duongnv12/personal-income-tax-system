<?php

namespace App\Http\Controllers;

use App\Models\TaxParameter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class TaxParameterController extends Controller
{
    /**
     * Hiển thị danh sách các tham số thuế để chỉnh sửa.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Lấy tất cả các tham số thuế từ database
        $parameters = TaxParameter::all();

        // Chuyển đổi thành dạng key-value để dễ truy cập trong view
        $parametersMap = $parameters->keyBy('param_key');

        return view('tax-parameters.index', compact('parametersMap'));
    }

    /**
     * Cập nhật các tham số thuế.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $validatedData = $request->validate([
                'gt_ban_than' => 'required|numeric|min:0',
                'gt_nguoi_phu_thuoc' => 'required|numeric|min:0',
                'bh_tl_tong' => 'required|numeric|min:0|max:1', // Tỷ lệ từ 0 đến 1
                'bh_tran_luong' => 'required|numeric|min:0',
                // Thêm các quy tắc xác thực cho các tham số khác nếu có
            ], [
                'required' => 'Trường :attribute là bắt buộc.',
                'numeric' => 'Trường :attribute phải là một số.',
                'min' => 'Trường :attribute phải lớn hơn hoặc bằng :min.',
                'max' => 'Trường :attribute phải nhỏ hơn hoặc bằng :max.',
            ]);

            DB::transaction(function () use ($validatedData) {
                // Cập nhật từng tham số
                foreach ($validatedData as $key => $value) {
                    TaxParameter::updateOrCreate(
                        ['param_key' => $key],
                        ['param_value' => $value]
                    );
                }
            });

            return redirect()->route('tax_parameters.index')->with('success', 'Đã cập nhật tham số thuế thành công.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình cập nhật: ' . $e->getMessage());
        }
    }
}