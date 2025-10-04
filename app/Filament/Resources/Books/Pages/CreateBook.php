<?php

namespace App\Filament\Resources\Books\Pages;

use App\Filament\Resources\Books\BookResource;
use App\Jobs\SendNewsletterNewBookJob;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;

    protected function afterCreate(): void
    {
        // Dispatch job to send newsletter emails when a new book is created
        SendNewsletterNewBookJob::dispatch($this->record->id);
    }
}
