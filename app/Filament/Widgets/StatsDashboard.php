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
        $countcostumer = \App\Models\Costumer::count();
        return [
            Stat::make(' Total Sparepart', $countsparepart),
            Stat::make('Stok Sparepart', $countstok_sparepart),
            Stat::make('Total Costumer', $countcostumer),
        ];
    }
}
