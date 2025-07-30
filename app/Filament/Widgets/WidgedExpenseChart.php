<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\PivotTable; // Ganti dengan model pivot milikmu
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WidgedExpenseChart extends ApexChartWidget
{
    protected static ?string $chartId = 'sparepart-out-monthly-chart';
    protected static ?string $heading = 'Pengeluaran Stok Sparepart Bulanan';

    protected function getOptions(): array
    {
        // Buat array 12 bulan terakhir
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        // Ambil data dari pivot (mis. pembayaran_items)
        $data = PivotTable::query()
            ->whereNotNull('sparepart_id')
            ->whereBetween('created_at', [
                now()->subMonths(12)->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->format('Y-m'))
            ->map(fn($group) => $group->sum('jumlah_sparepart'));

        // Siapkan data untuk grafik, isi 0 jika tidak ada data
        $chartData = $months->map(function ($month) use ($data) {
            return $data[$month] ?? 0;
        });

        return [
            'chart' => [
                'type' => 'bar', // atau 'area'
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Jumlah Sparepart Keluar',
                    'data' => $chartData->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $months->map(fn($month) => Carbon::parse($month)->format('M Y'))->toArray(),
                'labels' => [
                    'rotate' => -30,
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'title' => ['text' => 'Jumlah Sparepart'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#ef4444'], // merah
        ];
    }
}
