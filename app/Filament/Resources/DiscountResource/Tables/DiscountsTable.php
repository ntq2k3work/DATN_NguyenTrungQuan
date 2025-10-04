<?php

namespace App\Filament\Resources\DiscountResource\Tables;

use App\Models\Book;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DiscountsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('book.title')
                    ->label('Tên sách')
                    ->sortable()
                    ->searchable()
                    ->limit(50),

                TextColumn::make('percent')
                    ->label('Phần trăm giảm giá')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : 'N/A')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Số tiền giảm giá')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state) . ' VNĐ' : 'N/A')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Ngày kết thúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('book_id')
                    ->label('Sách')
                    ->options(Book::all()->pluck('title', 'id'))
                    ->searchable(),
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
                        ->modalHeading('Xác nhận xóa giảm giá')
                        ->modalDescription('Bạn có chắc chắn muốn xóa những giảm giá đã chọn?')
                        ->modalSubmitActionLabel('Xóa')
                        ->modalCancelActionLabel('Hủy'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
