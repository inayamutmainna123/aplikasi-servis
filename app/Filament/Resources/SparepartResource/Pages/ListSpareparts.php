<?php

namespace App\Filament\Resources\SparepartResource\Pages;

use App\Filament\Resources\SparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\SparepartExport;
use Maatwebsite\Excel\Facades\Excel;


class ListSpareparts extends ListRecords
{
    protected static string $resource = SparepartResource::class;

    protected function getHeaderActions(): array
    {
        //$queryString = request()->getQueryString();
        $decodeQueryString = urldecode(request()->getQueryString());

        return [
            Actions\CreateAction::make(),
            Actions\Action::make('exportToExcel')
                ->label('Ekspor ke Excel')
                ->action(fn() => Excel::download(new SparepartExport(), 'data_export.xlsx'))
                ->color('primary')
                ->button(),

        ];
    }
}
