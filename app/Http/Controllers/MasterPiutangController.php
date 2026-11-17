<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use App\Models\Piutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterPiutangController extends Controller
{
    public function index()
    {
        return view('master.piutang', [
            'borrowers' => Peminjam::query()->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $items = collect($request->input('items', []))->map(function ($item) {
            return [
                'barang' => $item['barang'] ?? null,
                'unit' => $item['unit'] ?? null,
                'harga' => $this->digits($item['harga'] ?? null),
            ];
        })->all();

        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'peminjam_id' => ['required', 'exists:peminjam,id'],
            'nomor_nota' => ['required', 'string', 'max:50'],
            'tanggal' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.barang' => ['required', 'string', 'max:150'],
            'items.*.unit' => ['required', 'integer', 'min:1'],
            'items.*.harga' => ['required', 'integer', 'min:0'],
        ], [
            'peminjam_id.required' => 'Peminjam wajib dipilih.',
            'peminjam_id.exists' => 'Peminjam tidak ditemukan.',
            'nomor_nota.required' => 'Nomor nota wajib diisi.',
            'nomor_nota.max' => 'Nomor nota maksimal 50 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'items.required' => 'Minimal satu barang harus diisi.',
            'items.min' => 'Minimal satu barang harus diisi.',
            'items.*.barang.required' => 'Nama barang wajib diisi.',
            'items.*.barang.max' => 'Nama barang maksimal 150 karakter.',
            'items.*.unit.required' => 'Unit wajib diisi.',
            'items.*.unit.integer' => 'Unit harus berupa angka.',
            'items.*.unit.min' => 'Unit minimal 1.',
            'items.*.harga.required' => 'Harga wajib diisi.',
            'items.*.harga.integer' => 'Harga harus berupa angka.',
            'items.*.harga.min' => 'Harga tidak boleh negatif.',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                Piutang::create([
                    'peminjam_id' => $validated['peminjam_id'],
                    'nomor_nota' => $validated['nomor_nota'],
                    'tanggal' => $validated['tanggal'],
                    'barang' => $item['barang'],
                    'unit' => $item['unit'],
                    'harga' => $item['harga'],
                    'total' => $item['harga'] * $item['unit'],
                ]);
            }
        });

        return redirect()->route('master.piutang.index')->with('status', 'Piutang berhasil ditambahkan.');
    }

    private function digits($value): string
    {
        return preg_replace('/\D/', '', (string) $value);
    }
}
