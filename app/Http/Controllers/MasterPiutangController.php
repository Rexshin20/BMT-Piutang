<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use App\Models\Piutang;
use Illuminate\Http\Request;

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
        Piutang::create($this->validated($request));

        return redirect()->route('master.piutang.index')->with('status', 'Piutang berhasil ditambahkan.');
    }

    private function validated(Request $request): array
    {
        $request->merge([
            'harga' => $this->digits($request->input('harga')),
        ]);

        $validated = $request->validate([
            'peminjam_id' => ['required', 'exists:peminjam,id'],
            'tanggal' => ['required', 'date'],
            'barang' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'integer', 'min:1'],
            'harga' => ['required', 'integer', 'min:0'],
        ], [
            'peminjam_id.required' => 'Peminjam wajib dipilih.',
            'peminjam_id.exists' => 'Peminjam tidak ditemukan.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'barang.required' => 'Barang wajib diisi.',
            'barang.max' => 'Barang maksimal 150 karakter.',
            'unit.required' => 'Unit wajib diisi.',
            'unit.integer' => 'Unit harus berupa angka.',
            'unit.min' => 'Unit minimal 1.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.integer' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
        ]);

        $validated['total'] = $validated['harga'] * $validated['unit'];

        return $validated;
    }

    private function digits($value): string
    {
        return preg_replace('/\D/', '', (string) $value);
    }
}
