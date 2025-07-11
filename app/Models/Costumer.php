<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    use HasUlids;
    protected $table = "costumer";
    protected $fillable = [
    'nama_costumer',
    'email_costumer',
    'no_hp_costumer',
    'alamat_costumer',
    ];

}
