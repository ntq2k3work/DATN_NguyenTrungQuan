<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckPasswordResetToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link đặt lại mật khẩu không hợp lệ.']);
        }

        // Kiểm tra token có tồn tại không
        $resetRecord = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Token đặt lại mật khẩu không hợp lệ hoặc đã bị xóa.']);
        }

        // Kiểm tra token có hết hạn không (60 phút)
        $tokenCreated = Carbon::parse($resetRecord->created_at);
        $isExpired = now()->isAfter($tokenCreated->addHours(1));

        if ($isExpired) {
            // Xóa token hết hạn
            DB::table('password_resets')
                ->where('email', $email)
                ->delete();

            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu email mới.']);
        }

        return $next($request);
    }
}
