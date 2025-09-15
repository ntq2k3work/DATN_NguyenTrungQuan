<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Tổng số sản phẩm
        $totalBooks = Book::count();

        // Tổng số người dùng (không bao gồm admin)
        $totalUsers = User::where('role', 'user')->count();

        // Tổng doanh thu từ các đơn hàng đã giao
        $totalRevenue = Order::where('status', 'delivered')
            ->sum('total');

        return [
            Stat::make('Tổng số sản phẩm', $totalBooks)
                ->description('Sách có sẵn trong hệ thống')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Tổng số người dùng', $totalUsers)
                ->description('Khách hàng đã đăng ký')
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart([15, 4, 10, 2, 12, 4, 12, 4]),

            Stat::make('Tổng doanh thu', $this->formatCurrency($totalRevenue))
                ->description('Từ các đơn hàng đã giao')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->chart([3, 4, 10, 2, 12, 4, 12, 4]),
        ];
    }

    /**
     * Format currency to Vietnamese Dong
     */
    private function formatCurrency($amount): string
    {
        return number_format($amount, 0, ',', '.') . ' VNĐ';
    }
}
