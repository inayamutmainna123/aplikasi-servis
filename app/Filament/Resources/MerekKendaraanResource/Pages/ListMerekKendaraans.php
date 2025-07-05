<?php

namespace App\Filament\Resources\MerekKendaraanResource\Pages;

use App\Filament\Resources\MerekKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMerekKendaraans extends ListRecords
{
    protected static string $resource = MerekKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
