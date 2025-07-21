<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PdfPembayaranController;
use App\pdf\Pembayaran;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('pembayaran', [Pembayaran::class, 'index'])->name('pembayaran');
Route::get('/pembayaran/{record}/cetak', [PdfPembayaranController::class, 'cetak'])->name('pembayaran.cetak');
