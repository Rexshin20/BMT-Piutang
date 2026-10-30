<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piutang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjam_id')->constrained('peminjam')->restrictOnDelete();
            $table->date('tanggal');
            $table->string('barang', 150);
            $table->unsignedBigInteger('harga');
            $table->unsignedBigInteger('total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piutang');
    }
};
