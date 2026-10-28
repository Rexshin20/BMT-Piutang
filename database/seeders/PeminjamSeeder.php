<?php

namespace Database\Seeders;

use App\Models\Peminjam;
use App\Support\DemoData;
use Illuminate\Database\Seeder;

class PeminjamSeeder extends Seeder
{
    public function run(): void
    {
        if (Peminjam::query()->exists()) {
            return;
        }

        foreach (DemoData::borrowers() as $borrower) {
            Peminjam::create($borrower);
        }
    }
}
