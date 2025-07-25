<?php

namespace App\Filament\Resources\SparepartResource\Pages;

use App\Filament\Resources\SparepartResource;
use Filament\Actions;
use Filament\Actions\ExportAction as ActionsExportAction;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;



class ListSpareparts extends ListRecords
{
    protected static string $resource = SparepartResource::class;

    protected function getHeaderActions(): array
    {
        //$queryString = request()->getQueryString();
        $decodeQueryString = urldecode(request()->getQueryString());

        return [
            Actions\Action::make('export')
                ->label('Export'),
            Actions\CreateAction::make(),

        ];
    }
}
