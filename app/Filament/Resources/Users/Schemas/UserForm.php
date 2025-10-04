<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Họ và tên')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Nhập họ và tên'),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('example@email.com')
                                    ->disabled(fn (string $context): bool => $context === 'edit')
                                    ->dehydrated(fn (string $context): bool => $context === 'create'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->label('Mật khẩu')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->placeholder('Tối thiểu 8 ký tự')
                                    ->visible(fn (string $context): bool => $context === 'create'),

                                TextInput::make('password_confirmation')
                                    ->label('Xác nhận mật khẩu')
                                    ->password()
                                    ->dehydrated(false)
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->same('password')
                                    ->placeholder('Nhập lại mật khẩu')
                                    ->visible(fn (string $context): bool => $context === 'create'),
                            ]),
                    ]),

                Section::make('Thông tin chi tiết')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('role')
                                    ->label('Vai trò')
                                    ->options([
                                        'user' => 'Người dùng',
                                        'admin' => 'Quản trị viên',
                                    ])
                                    ->required()
                                    ->default('user'),

                                DatePicker::make('date_of_birth')
                                    ->label('Ngày sinh')
                                    ->maxDate(now())
                                    ->placeholder('Chọn ngày sinh'),

                                Select::make('gender')
                                    ->label('Giới tính')
                                    ->options([
                                        'male' => 'Nam',
                                        'female' => 'Nữ',
                                        'other' => 'Khác',
                                    ])
                                    ->placeholder('Chọn giới tính'),
                            ]),

                        Textarea::make('address')
                            ->label('Địa chỉ')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Nhập địa chỉ chi tiết'),

                        Toggle::make('email_verified_at')
                            ->label('Đã xác thực email')
                            ->onIcon('heroicon-s-check-circle')
                            ->offIcon('heroicon-s-x-circle')
                            ->onColor('success')
                            ->offColor('danger')
                            ->dehydrateStateUsing(fn ($state) => $state ? now() : null)
                            ->dehydrated(fn ($state) => filled($state)),
                    ])
                    ->collapsible(),

                Section::make('Đổi mật khẩu')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('new_password')
                                    ->label('Mật khẩu mới')
                                    ->password()
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->minLength(8)
                                    ->placeholder('Để trống nếu không muốn đổi mật khẩu'),

                                TextInput::make('new_password_confirmation')
                                    ->label('Xác nhận mật khẩu mới')
                                    ->password()
                                    ->dehydrated(false)
                                    ->same('new_password')
                                    ->placeholder('Nhập lại mật khẩu mới'),
                            ]),
                    ])
                    ->collapsible()
                    ->visible(fn (string $context): bool => $context === 'edit'),
            ]);

    }
}
