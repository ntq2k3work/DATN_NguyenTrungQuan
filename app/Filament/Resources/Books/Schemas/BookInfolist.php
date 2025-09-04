<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Tên sách')
                                    ->weight('bold')
                                    ->size('lg'),

                                TextEntry::make('slug')
                                    ->label('Slug')
                                    ->copyable(),
                            ]),

                        TextEntry::make('description')
                            ->label('Mô tả')
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                Section::make('Thông tin chi tiết')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('price')
                                    ->label('Giá bán')
                                    ->money('VND')
                                    ->color('success'),

                                TextEntry::make('quantity')
                                    ->label('Số lượng')
                                    ->badge()
                                    ->color(fn (string $state): string => match (true) {
                                        $state > 10 => 'success',
                                        $state > 0 => 'warning',
                                        default => 'danger',
                                    }),

                                TextEntry::make('status')
                                    ->label('Trạng thái')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'active' => 'success',
                                        'inactive' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'active' => 'Hoạt động',
                                        'inactive' => 'Không hoạt động',
                                        default => $state,
                                    }),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('category.name')
                                    ->label('Danh mục')
                                    ->badge()
                                    ->color('primary'),

                                TextEntry::make('author.name')
                                    ->label('Tác giả')
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('publisher.name')
                                    ->label('Nhà xuất bản')
                                    ->badge()
                                    ->color('warning'),
                            ]),
                    ]),

                Section::make('Hình ảnh')
                    ->schema([
                        ImageEntry::make('image_url')
                            ->label('Hình ảnh sách')
                            ->size(300)
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
