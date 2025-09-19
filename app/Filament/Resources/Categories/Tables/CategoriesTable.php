<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Category;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->label('Đường dẫn URL')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('children_count')
                    ->label('Số danh mục con')
                    ->counts('children')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('books_count')
                    ->label('Số sách')
                    ->counts('books')
                    ->sortable()
                    ->badge()
                    ->color('success'),

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
                SelectFilter::make('parent_id')
                    ->label('Danh mục cha')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->placeholder('Tất cả'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Sửa')
                    ->icon('heroicon-o-pencil'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Xóa đã chọn')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận xóa danh mục')
                        ->modalDescription('Bạn có chắc chắn muốn xóa những danh mục đã chọn?')
                        ->modalSubmitActionLabel('Xóa')
                        ->modalCancelActionLabel('Hủy'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
