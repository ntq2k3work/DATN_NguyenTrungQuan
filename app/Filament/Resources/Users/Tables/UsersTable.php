<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
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
                    ->label('Họ và tên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                BadgeColumn::make('role')
                    ->label('Vai trò')
                    ->colors([
                        'primary' => 'user',
                        'danger' => 'admin',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'user' => 'Người dùng',
                        'admin' => 'Quản trị viên',
                        default => $state,
                    }),

                TextColumn::make('date_of_birth')
                    ->label('Ngày sinh')
                    ->date('d/m/Y')
                    ->sortable(),

                BadgeColumn::make('gender')
                    ->label('Giới tính')
                    ->colors([
                        'info' => 'male',
                        'warning' => 'female',
                        'gray' => 'other',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'male' => 'Nam',
                        'female' => 'Nữ',
                        'other' => 'Khác',
                        default => $state,
                    }),

                IconColumn::make('email_verified_at')
                    ->label('Trạng thái')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('last_access')
                    ->label('Truy cập gần đây')
                    ->dateTime('d/m/Y H:i', 'Asia/Ho_Chi_Minh')
                    ->sortable()
                    ->placeholder('Chưa truy cập'),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i', 'Asia/Ho_Chi_Minh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Cập nhật lần cuối')
                    ->dateTime('d/m/Y H:i', 'Asia/Ho_Chi_Minh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Vai trò')
                    ->options([
                        'user' => 'Người dùng',
                        'admin' => 'Quản trị viên',
                    ]),

                SelectFilter::make('gender')
                    ->label('Giới tính')
                    ->options([
                        'male' => 'Nam',
                        'female' => 'Nữ',
                        'other' => 'Khác',
                    ]),

                TernaryFilter::make('email_verified_at')
                    ->label('Trạng thái xác thực')
                    ->placeholder('Tất cả')
                    ->trueLabel('Đã xác thực')
                    ->falseLabel('Chưa xác thực'),

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
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Xem')
                        ->icon('heroicon-o-eye'),

                    EditAction::make()
                        ->label('Sửa')
                        ->icon('heroicon-o-pencil'),

                    Action::make('toggle_status')
                        ->label('Đổi trạng thái')
                        ->icon('heroicon-o-check-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận thay đổi trạng thái')
                        ->modalDescription('Bạn có chắc chắn muốn thay đổi trạng thái xác thực email của người dùng này?')
                        ->modalSubmitActionLabel('Xác nhận')
                        ->modalCancelActionLabel('Hủy')
                        ->action(function (User $record) {
                        // Không cho phép thay đổi trạng thái chính mình
                        if ($record->id === Auth::id()) {
                                Notification::make()
                                    ->title('Lỗi')
                                    ->body('Không thể thay đổi trạng thái của chính mình!')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $record->update([
                                'email_verified_at' => $record->email_verified_at ? null : now()
                            ]);

                            $status = $record->email_verified_at ? 'kích hoạt' : 'vô hiệu hóa';

                            Notification::make()
                                ->title('Thành công')
                                ->body("Đã {$status} người dùng thành công!")
                                ->success()
                                ->send();
                        }),
                ])
                ->label('Hành động')
                ->icon('heroicon-o-ellipsis-vertical')
                ->button()
                ->color('gray')
                ->size('sm'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Xóa đã chọn')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận xóa nhiều người dùng')
                        ->modalDescription('Bạn có chắc chắn muốn xóa những người dùng đã chọn? Hành động này không thể hoàn tác.')
                        ->modalSubmitActionLabel('Xóa')
                        ->modalCancelActionLabel('Hủy')
                        ->before(function ($records) {
                            $currentUserId = Auth::id();
                            $hasOrders = $records->some(function ($record) use ($currentUserId) {
                                return $record->id === $currentUserId || $record->orders()->exists();
                            });

                            if ($hasOrders) {
                                Notification::make()
                                    ->title('Lỗi')
                                    ->body('Không thể xóa người dùng đã có đơn hàng hoặc chính mình!')
                                    ->danger()
                                    ->send();
                                return false;
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
