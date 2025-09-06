<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('order_number')
                    ->label('Mã đơn hàng')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Khách vãng lai'),

                TextColumn::make('full_name')
                    ->label('Họ và tên')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('SĐT')
                    ->searchable()
                    ->copyable(),

                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'shipped',
                        'primary' => 'delivered',
                        'danger' => 'cancelled',
                        'gray' => 'returned',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý',
                        'shipped' => 'Đã giao hàng',
                        'delivered' => 'Đã nhận hàng',
                        'cancelled' => 'Đã hủy',
                        'returned' => 'Đã trả hàng',
                        default => $state,
                    }),

                TextColumn::make('total')
                    ->label('Tổng tiền')
                    ->money('VND')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('items_count')
                    ->label('Số sản phẩm')
                    ->counts('items')
                    ->sortable(),

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
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý',
                        'shipped' => 'Đã giao hàng',
                        'delivered' => 'Đã nhận hàng',
                        'cancelled' => 'Đã hủy',
                        'returned' => 'Đã trả hàng',
                    ]),

                SelectFilter::make('payment_method')
                    ->label('Phương thức thanh toán')
                    ->options([
                        'cod' => 'Thanh toán khi nhận hàng',
                        'bank_transfer' => 'Chuyển khoản ngân hàng',
                        'credit_card' => 'Thẻ tín dụng',
                        'momo' => 'Ví MoMo',
                    ]),

                SelectFilter::make('shipping_method')
                    ->label('Phương thức vận chuyển')
                    ->options([
                        'standard' => 'Giao hàng tiêu chuẩn',
                        'express' => 'Giao hàng nhanh',
                        'pickup' => 'Nhận tại cửa hàng',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Từ ngày'),
                        DatePicker::make('created_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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

                Action::make('mark_delivered')
                    ->label('Đánh dấu đã giao')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Xác nhận đã giao hàng')
                    ->modalDescription('Bạn có chắc chắn muốn đánh dấu đơn hàng này là đã giao hàng?')
                    ->modalSubmitActionLabel('Xác nhận')
                    ->modalCancelActionLabel('Hủy')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['pending', 'processing', 'shipped']))
                    ->action(function (Order $record) {
                        $record->update(['status' => 'delivered']);

                        Notification::make()
                            ->title('Thành công')
                            ->body('Đã đánh dấu đơn hàng là đã giao hàng!')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Xóa đã chọn')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận xóa nhiều đơn hàng')
                        ->modalDescription('Bạn có chắc chắn muốn xóa những đơn hàng đã chọn? Hành động này không thể hoàn tác.')
                        ->modalSubmitActionLabel('Xóa')
                        ->modalCancelActionLabel('Hủy'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
