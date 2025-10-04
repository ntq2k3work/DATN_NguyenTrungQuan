<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscount extends EditRecord
{
    protected static string $resource = DiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Xóa giảm giá'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Giảm giá đã được cập nhật thành công';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Giảm giá đã được xóa thành công';
    }
}
