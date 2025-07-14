<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Sparepart;
use Filament\Notifications\Notification;

class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function afterCreate(): void
    {
        $pembayaran = $this->record;

        foreach ($pembayaran->items as $item) {
            // dd($item);
            if ($item->sparepart_id && $item->jumlah_sparepart > 0) {
                $sparepart = Sparepart::find($item->sparepart_id);
                if ($sparepart) {
                    $sparepart->stok_sparepart -= (int) $item->jumlah_sparepart;
                    if ($sparepart->stok_sparepart < 0) {
                        $sparepart->stok_sparepart = 0; // opsional: cegah negatif
                    }
                    $sparepart->save();
                }
            }
        }

        Notification::make()
            ->title('Pembayaran berhasil!')
            ->body('Stok sparepart berhasil dikurangi.')
            ->success()
            ->send();
    }
}
