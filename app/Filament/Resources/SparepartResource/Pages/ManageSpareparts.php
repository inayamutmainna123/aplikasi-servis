<?php

namespace App\Filament\Resources\SparepartResource\Pages;

use App\Filament\Resources\SparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSpareparts extends ManageRecords
{
    protected static string $resource = SparepartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
