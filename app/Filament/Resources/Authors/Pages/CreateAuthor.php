<?php

namespace App\Filament\Resources\Authors\Pages;

use App\Filament\Resources\Authors\AuthorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthor extends CreateRecord
{
    protected static string $resource = AuthorResource::class;

    protected static ?string $title = 'Tạo tác giả';

    protected static ?string $navigationLabel = 'Tạo tác giả';

    protected static ?string $breadcrumb = 'Tạo tác giả';

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tác giả đã được tạo thành công';
    }
}
