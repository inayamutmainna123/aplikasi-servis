<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $countsparepart = \App\Models\Sparepart::count();
        $countstok_sparepart = \App\Models\Sparepart::sum('stok_sparepart');
        $countservicedetail = \App\Models\ServiceDetail::count();
        return [
            Stat::make(' Total Sparepart', $countsparepart),
            Stat::make('Total Stok Sparepart', $countstok_sparepart),
            Stat::make('Total Yang Di Service', $countservicedetail),
        ];
    }
}
