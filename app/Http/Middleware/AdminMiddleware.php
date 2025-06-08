<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Nếu không phải admin, chuyển hướng về dashboard hoặc trang lỗi 403
        return redirect('/dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
        // Hoặc: abort(403, 'Unauthorized action.');
    }
}