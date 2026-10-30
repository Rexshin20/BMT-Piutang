<?php

namespace Database\Seeders;

use App\Models\Peminjam;
use App\Models\Piutang;
use App\Support\DemoData;
use Illuminate\Database\Seeder;

class PiutangSeeder extends Seeder
{
    public function run(): void
    {
        if (Piutang::query()->exists()) {
            return;
        }

        $borrowers = Peminjam::query()->pluck('id', 'nama');

        foreach (DemoData::receivables() as $receivable) {
            $borrowerId = $borrowers[$receivable['nama']] ?? null;

            if ($borrowerId === null) {
                continue;
            }

            Piutang::create([
                'peminjam_id' => $borrowerId,
                'tanggal' => $receivable['tanggal'],
                'barang' => $receivable['barang'],
                'harga' => $receivable['harga'],
                'total' => $receivable['total'],
            ]);
        }
    }
}
