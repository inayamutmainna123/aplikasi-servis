<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum StatusServiceDetail: string implements HasLabel
{
    case BelumDiperbaiki = 'belum diperbaiki';
    case SedangDiperbaiki = 'sedang diperbaiki';
    case SelesaiDiperbaiki = 'selesai diperbaiki';

    // Untuk kebutuhan tampilan label di Filament
    public function getLabel(): ?string
    {
        return match ($this) {
            self::BelumDiperbaiki => 'Belum Diperbaiki',
            self::SedangDiperbaiki => 'Sedang Diperbaiki',
            self::SelesaiDiperbaiki => 'Selesai Diperbaiki',
        };
    }

    // (Opsional) Jika ingin label enum untuk judul atau grouping
    public static function label(): string
    {
        return "Status Service";
    }
}
