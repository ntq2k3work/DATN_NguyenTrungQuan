<?php

namespace App\Filament\Resources\DiscountResource\Schemas;

use App\Models\Book;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DiscountForm
{
    public static function make(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('book_id')
                    ->label('Sách')
                    ->options(Book::all()->pluck('title', 'id'))
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('percent')
                    ->label('Phần trăm giảm giá (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->nullable()
                    ->columnSpan(1),

                TextInput::make('amount')
                    ->label('Số tiền giảm giá (VNĐ)')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('VNĐ')
                    ->nullable()
                    ->columnSpan(1),

                DateTimePicker::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->nullable()
                    ->columnSpan(1),

                DateTimePicker::make('end_date')
                    ->label('Ngày kết thúc')
                    ->nullable()
                    ->columnSpan(1),
            ])
            ->columns(2);
    }
}
