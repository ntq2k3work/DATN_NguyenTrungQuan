<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Họ và tên')
                                    ->weight('bold')
                                    ->size('lg'),

                                TextEntry::make('email')
                                    ->label('Email')
                                    ->copyable(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('role')
                                    ->label('Vai trò')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'user' => 'primary',
                                        'admin' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'user' => 'Người dùng',
                                        'admin' => 'Quản trị viên',
                                        default => $state,
                                    }),

                                IconEntry::make('email_verified_at')
                                    ->label('Trạng thái xác thực')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger'),
                            ]),
                    ]),

                Section::make('Thông tin chi tiết')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('date_of_birth')
                                    ->label('Ngày sinh')
                                    ->date('d/m/Y'),

                                TextEntry::make('gender')
                                    ->label('Giới tính')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'male' => 'info',
                                        'female' => 'warning',
                                        'other' => 'gray',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'male' => 'Nam',
                                        'female' => 'Nữ',
                                        'other' => 'Khác',
                                        default => $state,
                                    }),

                                TextEntry::make('orders_count')
                                    ->label('Số đơn hàng')
                                    ->badge()
                                    ->color('primary'),
                            ]),

                        TextEntry::make('address')
                            ->label('Địa chỉ')
                            ->markdown()
                            ->columnSpanFull(),
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
