<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class MerekKendaraan extends Model
{
    use HasUlids;
    protected $table = "merek_kendaraan";

    protected $fillable = [
        'kode_merek_kendaraan',
        'merek_kendaraan',
    ];
}
