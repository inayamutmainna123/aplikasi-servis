<?php

namespace App\Filament\Resources\SparepartResource\Pages;

use App\Filament\Resources\SparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpareparts extends ListRecords
{
    protected static string $resource = SparepartResource::class;

    protected function getHeaderActions(): array
    {
        //$queryString = request()->getQueryString();
        $decodeQueryString = urldecode(request()->getQueryString());

        return [
            Actions\CreateAction::make(),
        ];
    }
}
