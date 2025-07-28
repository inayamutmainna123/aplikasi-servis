<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
   use HasUlids;
   protected $table = "sparepart";

   protected $fillable = [
      'gambar',
      'nama_sparepart',
      'harga_sparepart',
      'stok_sparepart',
      'deskripsi',
   ];
}
