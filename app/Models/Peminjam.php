<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjam extends Model
{
    protected $table = 'peminjam';

    protected $fillable = ['nama', 'alamat', 'telepon'];

    public function piutang(): HasMany
    {
        return $this->hasMany(Piutang::class);
    }
}
