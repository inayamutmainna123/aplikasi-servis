<?php

namespace App\Filament\Resources\CostumerResource\Pages;

use App\Filament\Resources\CostumerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCostumers extends ManageRecords
{
    protected static string $resource = CostumerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
