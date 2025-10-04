<?php

namespace App\Filament\Resources\Authors\Pages;

use App\Filament\Resources\Authors\AuthorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthor extends EditRecord
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Xóa tác giả'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Tác giả đã được cập nhật thành công';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Tác giả đã được xóa thành công';
    }
}
