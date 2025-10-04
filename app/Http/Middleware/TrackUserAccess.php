<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ track cho user đã đăng nhập
        if (Auth::check()) {
            $user = Auth::user();
            $today = now()->format('Y-m-d');
            $cacheKey = "user_access_{$user->id}_{$today}";

            // Kiểm tra xem đã update hôm nay chưa
            if (!Cache::has($cacheKey)) {
                // Update last_access chỉ 1 lần mỗi ngày
                $user->update(['last_access' => now()]);

                // Cache để tránh update nhiều lần trong ngày
                Cache::put($cacheKey, true, now()->endOfDay());
            }
        }

        return $next($request);
    }
}
