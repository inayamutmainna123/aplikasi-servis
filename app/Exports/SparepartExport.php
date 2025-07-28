<?php

namespace App\Exports;

use App\Models\Sparepart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SparepartExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Sparepart::all();
    }

    public function headings(): array
    {
        return [
            'Nama Sparepart',
            'Harga Sparepart',
            'Stok Sparepart',
        ];
    }

    public function map($sparepart): array
    {
        return [
            $sparepart->nama_sparepart,
            $sparepart->harga_sparepart,
            $sparepart->stok_sparepart,
        ];
    }
}
