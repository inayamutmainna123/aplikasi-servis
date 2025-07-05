<?php

namespace App\Filament\Resources\ServiceDetailResource\Pages;

use App\Filament\Resources\ServiceDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceDetail extends EditRecord
{
    protected static string $resource = ServiceDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
