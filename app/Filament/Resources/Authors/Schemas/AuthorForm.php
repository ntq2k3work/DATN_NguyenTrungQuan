<?php

namespace App\Filament\Resources\Authors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AuthorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên tác giả')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Nhập tên tác giả'),
                Textarea::make('bio')
                    ->label('Tiểu sử')
                    ->rows(4)
                    ->maxLength(1000)
                    ->placeholder('Nhập tiểu sử của tác giả')
                    ->columnSpanFull(),
            ]);
    }
}
