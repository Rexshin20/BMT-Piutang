<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = ['nomor_nota', 'tanggal', 'jumlah', 'keterangan'];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'integer',
    ];
}
