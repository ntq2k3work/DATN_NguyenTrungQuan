<?php

namespace App\Filament\Resources\Publishers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PublisherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin nhà xuất bản')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên nhà xuất bản')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nhập tên nhà xuất bản')
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
