<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Piutang extends Model
{
    protected $table = 'piutang';

    protected $fillable = ['peminjam_id', 'nomor_nota', 'tanggal', 'barang', 'unit', 'harga', 'total', 'lunas_at'];

    protected $casts = [
        'tanggal' => 'date',
        'unit' => 'integer',
        'harga' => 'integer',
        'total' => 'integer',
        'lunas_at' => 'datetime',
    ];

    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(Peminjam::class);
    }
}
