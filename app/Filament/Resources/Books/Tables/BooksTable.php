<?php

namespace App\Filament\Resources\Books\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Tên sách')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50),

                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('author.name')
                    ->label('Tác giả')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('publisher.name')
                    ->label('Nhà xuất bản')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Giá bán')
                    ->money('VND')
                    ->sortable()
                    ->color('success'),

                TextColumn::make('quantity')
                    ->label('Số lượng')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    }),

                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                        default => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Cập nhật lần cuối')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'name')
                    ->searchable(),

                SelectFilter::make('author_id')
                    ->label('Tác giả')
                    ->relationship('author', 'name')
                    ->searchable(),

                SelectFilter::make('publisher_id')
                    ->label('Nhà xuất bản')
                    ->relationship('publisher', 'name')
                    ->searchable(),

                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                    ]),

                Filter::make('price_range')
                    ->form([
                        TextInput::make('price_from')
                            ->label('Giá từ')
                            ->numeric()
                            ->placeholder('0'),
                        TextInput::make('price_to')
                            ->label('Giá đến')
                            ->numeric()
                            ->placeholder('1000000'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['price_from'],
                                fn ($query, $price) => $query->where('price', '>=', $price)
                            )
                            ->when(
                                $data['price_to'],
                                fn ($query, $price) => $query->where('price', '<=', $price)
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xem')
                    ->icon('heroicon-o-eye'),

                EditAction::make()
                    ->label('Sửa')
                    ->icon('heroicon-o-pencil'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Xóa đã chọn')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận xóa sách')
                        ->modalDescription('Bạn có chắc chắn muốn xóa những sách đã chọn?')
                        ->modalSubmitActionLabel('Xóa')
                        ->modalCancelActionLabel('Hủy'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
