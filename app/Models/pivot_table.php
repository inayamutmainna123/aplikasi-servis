<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class pivot_table extends Model
{
    use HasUlids;
    protected $table = "pivot_table";
    protected $fillable = [
        "costumer_id",
        "sparepart_id",
        "service_item_id",
        "service_detail_id",
    ];
    
}
