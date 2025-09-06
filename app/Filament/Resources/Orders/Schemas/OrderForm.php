<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use App\Models\User;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin đơn hàng')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('order_number')
                                    ->label('Mã đơn hàng')
                                    ->required()
                                    ->maxLength(255)
                                    ->default(fn () => \App\Models\Order::generateOrderNumber())
                                    ->placeholder('Tự động tạo'),

                                Select::make('user_id')
                                    ->label('Khách hàng')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Chọn khách hàng'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('full_name')
                                    ->label('Họ và tên')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Nhập họ và tên'),

                                TextInput::make('phone')
                                    ->label('Số điện thoại')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20)
                                    ->placeholder('Nhập số điện thoại'),
                            ]),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nhập email'),

                        Textarea::make('address')
                            ->label('Địa chỉ giao hàng')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Nhập địa chỉ chi tiết'),
                    ]),

                Section::make('Thông tin thanh toán và vận chuyển')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('shipping_method')
                                    ->label('Phương thức vận chuyển')
                                    ->options([
                                        'standard' => 'Giao hàng tiêu chuẩn',
                                        'express' => 'Giao hàng nhanh',
                                        'pickup' => 'Nhận tại cửa hàng',
                                    ])
                                    ->required()
                                    ->default('standard'),

                                Select::make('payment_method')
                                    ->label('Phương thức thanh toán')
                                    ->options([
                                        'cod' => 'Thanh toán khi nhận hàng',
                                        'bank_transfer' => 'Chuyển khoản ngân hàng',
                                        'credit_card' => 'Thẻ tín dụng',
                                        'momo' => 'Ví MoMo',
                                    ])
                                    ->required()
                                    ->default('cod'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label('Tạm tính')
                                    ->numeric()
                                    ->prefix('₫')
                                    ->required()
                                    ->placeholder('0'),

                                TextInput::make('shipping_fee')
                                    ->label('Phí vận chuyển')
                                    ->numeric()
                                    ->prefix('₫')
                                    ->default(0)
                                    ->placeholder('0'),

                                TextInput::make('total')
                                    ->label('Tổng cộng')
                                    ->numeric()
                                    ->prefix('₫')
                                    ->required()
                                    ->placeholder('0'),
                            ]),
                    ]),

                Section::make('Trạng thái và ghi chú')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Trạng thái đơn hàng')
                                    ->options([
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đã giao hàng',
                                        'delivered' => 'Đã nhận hàng',
                                        'cancelled' => 'Đã hủy',
                                        'returned' => 'Đã trả hàng',
                                    ])
                                    ->required()
                                    ->default('pending'),

                                Textarea::make('notes')
                                    ->label('Ghi chú')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->placeholder('Ghi chú thêm về đơn hàng'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
