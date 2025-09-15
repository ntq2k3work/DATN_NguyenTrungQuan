<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class WeeklyTrafficWidget extends ChartWidget
{
    protected ?string $heading = 'Lưu Lượng Truy Cập Theo Tuần';

    protected function getData(): array
    {
        $currentYear = now()->year;
        $currentWeek = now()->weekOfYear;

        // Lấy dữ liệu truy cập theo tuần trong năm hiện tại
        $weeklyTraffic = User::select(
                DB::raw('WEEK(last_access, 1) as week'),
                DB::raw('COUNT(*) as visits')
            )
            ->whereYear('last_access', $currentYear)
            ->whereNotNull('last_access')
            ->groupBy(DB::raw('WEEK(last_access, 1)'))
            ->orderBy('week')
            ->get()
            ->pluck('visits', 'week')
            ->toArray();

        // Tạo dữ liệu cho 12 tuần gần nhất
        $trafficData = [];
        $weekLabels = [];

        for ($i = max(1, $currentWeek - 11); $i <= $currentWeek; $i++) {
            $visits = $weeklyTraffic[$i] ?? 0;
            $trafficData[] = $visits;
            $weekLabels[] = "Tuần " . $i;
        }

        // Đảm bảo có ít nhất một giá trị > 0 để trục Y hiển thị
        if (max($trafficData) == 0) {
            $trafficData[count($trafficData) - 1] = 1; // Thêm giá trị mẫu
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số lượt truy cập',
                    'data' => $trafficData,
                    'fill' => true,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.4
                ],
            ],
            'labels' => $weekLabels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.dataset.label + ": " + context.parsed.y + " lượt truy cập";
                        }'
                    ]
                ]
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Tuần'
                    ]
                ],
                'y' => [
                    'display' => true,
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Số lượt truy cập'
                    ],
                    'ticks' => [
                        'display' => true,
                        'stepSize' => 1,
                        'maxTicksLimit' => 10,
                        'callback' => 'function(value) {
                            return value + " lượt";
                        }'
                    ],
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.1)'
                    ]
                ]
            ]
        ];
    }
}
