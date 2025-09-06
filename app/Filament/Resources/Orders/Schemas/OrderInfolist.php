<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin đơn hàng')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('order_number')
                                    ->label('Mã đơn hàng')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->copyable(),

                                TextEntry::make('status')
                                    ->label('Trạng thái')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'success',
                                        'delivered' => 'primary',
                                        'cancelled' => 'danger',
                                        'returned' => 'gray',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đã giao hàng',
                                        'delivered' => 'Đã nhận hàng',
                                        'cancelled' => 'Đã hủy',
                                        'returned' => 'Đã trả hàng',
                                        default => $state,
                                    }),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Khách hàng')
                                    ->placeholder('Khách vãng lai'),

                                TextEntry::make('total')
                                    ->label('Tổng tiền')
                                    ->money('VND')
                                    ->weight('bold')
                                    ->size('lg'),
                            ]),
                    ]),

                Section::make('Thông tin khách hàng')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('full_name')
                                    ->label('Họ và tên'),

                                TextEntry::make('phone')
                                    ->label('Số điện thoại')
                                    ->copyable(),
                            ]),

                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),

                        TextEntry::make('address')
                            ->label('Địa chỉ giao hàng')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Chi tiết đơn hàng')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('Sản phẩm')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('book.title')
                                            ->label('Tên sách')
                                            ->weight('bold'),

                                        TextEntry::make('quantity')
                                            ->label('Số lượng')
                                            ->badge()
                                            ->color('primary'),

                                        TextEntry::make('price')
                                            ->label('Giá')
                                            ->money('VND'),

                                        TextEntry::make('total')
                                            ->label('Thành tiền')
                                            ->money('VND')
                                            ->weight('bold'),
                                    ]),
                            ])
                            ->columns(1),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('subtotal')
                                    ->label('Tạm tính')
                                    ->money('VND'),

                                TextEntry::make('shipping_fee')
                                    ->label('Phí vận chuyển')
                                    ->money('VND'),

                                TextEntry::make('total')
                                    ->label('Tổng cộng')
                                    ->money('VND')
                                    ->weight('bold')
                                    ->size('lg'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Thông tin thanh toán và vận chuyển')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('payment_method')
                                    ->label('Phương thức thanh toán')
                                    ->badge()
                                    ->color('info')
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'cod' => 'Thanh toán khi nhận hàng',
                                        'bank_transfer' => 'Chuyển khoản ngân hàng',
                                        'credit_card' => 'Thẻ tín dụng',
                                        'momo' => 'Ví MoMo',
                                        default => $state,
                                    }),

                                TextEntry::make('shipping_method')
                                    ->label('Phương thức vận chuyển')
                                    ->badge()
                                    ->color('success')
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'standard' => 'Giao hàng tiêu chuẩn',
                                        'express' => 'Giao hàng nhanh',
                                        'pickup' => 'Nhận tại cửa hàng',
                                        default => $state,
                                    }),
                            ]),

                        TextEntry::make('notes')
                            ->label('Ghi chú')
                            ->columnSpanFull()
                            ->placeholder('Không có ghi chú'),
                    ])
                    ->collapsible(),

                Section::make('Thông tin hệ thống')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Ngày tạo')
                                    ->dateTime('d/m/Y H:i'),

                                TextEntry::make('updated_at')
                                    ->label('Cập nhật lần cuối')
                                    ->dateTime('d/m/Y H:i'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
