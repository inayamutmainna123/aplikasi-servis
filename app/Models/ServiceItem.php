<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasUlids;
    protected $table = "service_item";

    protected $fillable = [
        'nama_service',
        'harga_service',
        'deskripsi',
    ];
}
