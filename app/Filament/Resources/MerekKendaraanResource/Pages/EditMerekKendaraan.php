<?php

namespace App\Filament\Resources\MerekKendaraanResource\Pages;

use App\Filament\Resources\MerekKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMerekKendaraan extends EditRecord
{
    protected static string $resource = MerekKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
