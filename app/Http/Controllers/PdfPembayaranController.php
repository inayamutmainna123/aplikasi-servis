<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfPembayaranController extends Controller
{
    public function cetak($id)
    {
        $pembayaran = Pembayaran::with(['costumer'])->findOrFail($id);

        $pdf = Pdf::loadView('livewire.pdf.pembayaran', compact('pembayaran'))->setPaper('A4');

        return $pdf->stream('pembayaran-' . $pembayaran->id . '.pdf');
    }
}
