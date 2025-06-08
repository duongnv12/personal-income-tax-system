<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User; // <-- Đảm bảo đã import Model User

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Lấy thông tin người dùng đã đăng nhập
        $user = Auth::user();

        // Kiểm tra vai trò của người dùng và chuyển hướng
        if ($user && $user->isAdmin()) {
            // Nếu là Admin, chuyển hướng đến trang dashboard admin (hoặc trang cấu hình thuế)
            return redirect()->intended(route('admin.dashboard')); // Hoặc một route admin dashboard khác bạn tạo
        } else {
            // Nếu là người dùng thông thường, chuyển hướng đến trang tính thuế
            return redirect()->intended(route('dashboard')); // Trang tính thuế của user
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}