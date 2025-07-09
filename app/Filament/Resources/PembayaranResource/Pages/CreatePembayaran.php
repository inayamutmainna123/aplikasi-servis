<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePembayaran extends CreateRecord
{
     public static string $resource = PembayaranResource::class;
    // HAPUS fungsi mutateFormDataBeforeCreate karena tidak dibutuhkan
}
