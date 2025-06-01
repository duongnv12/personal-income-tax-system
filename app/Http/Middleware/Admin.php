<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->is_admin || !Auth::user()->is_active) {
            // Nếu không phải admin, hoặc không active
            Auth::logout(); // Đăng xuất người dùng nếu không hợp lệ
            return redirect('/login')->withErrors(['email' => 'Tài khoản của bạn không có quyền truy cập hoặc đã bị khóa.']);
        }

        return $next($request);
    }
}