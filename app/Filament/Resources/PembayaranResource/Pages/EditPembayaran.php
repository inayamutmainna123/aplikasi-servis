<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Resources\Pages\EditRecord;
use PembayaranResource as GlobalPembayaranResource;

class EditPembayaran extends EditRecord
{
    protected static string $resource = PembayaranResource::class;

    //protected function mutateFormDataBeforeSave(array $data): array//
   // {
        //$data['total_harga'] = collect($data['items'])->sum(fn ($item) =>
            //($item['harga_service'] ?? 0) + ($item['harga_sparepart'] ?? 0)
       // );//

        //$data['total_kembalian'] = $data['total_bayar'] - $data['total_harga'];

        //return $data;//
    //}//
}
