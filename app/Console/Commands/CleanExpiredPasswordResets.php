<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanExpiredPasswordResets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dọn dẹp các token đặt lại mật khẩu đã hết hạn';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu dọn dẹp token đặt lại mật khẩu đã hết hạn...');

        // Xóa các token đã hết hạn (hơn 60 phút)
        $deletedCount = DB::table('password_resets')
            ->where('created_at', '<', now()->subHours(1))
            ->delete();

        if ($deletedCount > 0) {
            $this->info("Đã xóa {$deletedCount} token hết hạn");
        } else {
            $this->info("ℹ️ Không có token nào cần dọn dẹp");
        }

        $this->info('✨ Hoàn thành dọn dẹp!');

        return 0;
    }
}
