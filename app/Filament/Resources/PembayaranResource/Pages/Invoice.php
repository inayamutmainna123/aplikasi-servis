<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Resources\Pages\Page;

class Invoice extends Page
{
    protected static string $resource = PembayaranResource::class;

    protected static string $view = 'filament.resources.pembayaran-resource.pages.invoice';
}
