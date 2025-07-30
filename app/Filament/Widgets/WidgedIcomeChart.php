<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Sparepart;

class WidgedIcomeChart extends ApexChartWidget
{
    protected static ?string $chartId = 'sparepart-stock';
    protected static ?string $heading = 'Stok Sparepart Saat Ini';

    protected function getOptions(): array
    {
        // Ambil semua sparepart & stoknya
        $spareparts = Sparepart::all();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Stok Saat Ini',
                    'data' => $spareparts->pluck('stok_sparepart')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $spareparts->pluck('nama_sparepart')->toArray(),
                'labels' => [
                    'rotate' => -30,
                    'style' => [
                        'fontWeight' => 600,
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'title' => ['text' => 'Unit'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#3b82f6'], // biru
        ];
    }
}
