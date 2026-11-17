<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Peminjam;
use App\Models\Piutang;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function index()
    {
        $receivables = Piutang::query()
            ->with('peminjam')
            ->whereNull('lunas_at')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $paidByNota = Pembayaran::query()
            ->selectRaw('nomor_nota, SUM(jumlah) as total')
            ->groupBy('nomor_nota')
            ->pluck('total', 'nomor_nota');

        $remaining = [];

        foreach ($receivables as $receivable) {
            $receivable->dibayar = 0;
            $receivable->sisa = $receivable->total;
        }

        foreach ($receivables->sortBy('id') as $receivable) {
            $notaKey = $receivable->nomor_nota ?? ('#' . $receivable->id);

            if (!array_key_exists($notaKey, $remaining)) {
                $remaining[$notaKey] = (int) ($paidByNota[$notaKey] ?? 0);
            }

            $applied = min($receivable->total, $remaining[$notaKey]);
            $receivable->dibayar = $applied;
            $receivable->sisa = $receivable->total - $applied;
            $remaining[$notaKey] -= $applied;
        }

        return view('piutang', [
            'receivables' => $receivables,
            'borrowers' => Peminjam::query()->orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Piutang $piutang)
    {
        $request->merge(['harga' => preg_replace('/\D/', '', (string) $request->input('harga'))]);

        $validated = $request->validate([
            'peminjam_id' => ['required', 'exists:peminjam,id'],
            'nomor_nota' => ['required', 'string', 'max:50'],
            'tanggal' => ['required', 'date'],
            'barang' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'integer', 'min:1'],
            'harga' => ['required', 'integer', 'min:0'],
        ], [
            'peminjam_id.required' => 'Peminjam wajib dipilih.',
            'peminjam_id.exists' => 'Peminjam tidak ditemukan.',
            'nomor_nota.required' => 'Nomor nota wajib diisi.',
            'nomor_nota.max' => 'Nomor nota maksimal 50 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'barang.required' => 'Nama barang wajib diisi.',
            'barang.max' => 'Nama barang maksimal 150 karakter.',
            'unit.required' => 'Unit wajib diisi.',
            'unit.integer' => 'Unit harus berupa angka.',
            'unit.min' => 'Unit minimal 1.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.integer' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 0.',
        ]);

        $piutang->update([
            'peminjam_id' => $validated['peminjam_id'],
            'nomor_nota' => $validated['nomor_nota'],
            'tanggal' => $validated['tanggal'],
            'barang' => $validated['barang'],
            'unit' => $validated['unit'],
            'harga' => $validated['harga'],
            'total' => $validated['unit'] * $validated['harga'],
        ]);

        return redirect()->route('piutang.index')->with('status', 'Data piutang berhasil diperbarui.');
    }

    public function lunas()
    {
        return view('lunas', [
            'receivables' => Piutang::query()
                ->with('peminjam')
                ->whereNotNull('lunas_at')
                ->orderByDesc('lunas_at')
                ->get(),
        ]);
    }
}
