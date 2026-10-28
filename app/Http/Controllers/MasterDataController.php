<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        return view('master.peminjam', [
            'borrowers' => Peminjam::query()->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Peminjam::create($this->validated($request));

        return redirect()->route('master.borrowers')->with('status', 'Data peminjam berhasil ditambahkan.');
    }

    public function update(Request $request, Peminjam $peminjam)
    {
        $peminjam->update($this->validated($request));

        return redirect()->route('master.borrowers')->with('status', 'Data peminjam berhasil diperbarui.');
    }

    public function destroy(Peminjam $peminjam)
    {
        $peminjam->delete();

        return redirect()->route('master.borrowers')->with('status', 'Data peminjam berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'alamat' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:30'],
        ], [
            'nama.required' => 'Nama peminjam wajib diisi.',
            'nama.max' => 'Nama peminjam maksimal 100 karakter.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
            'telepon.required' => 'No HP wajib diisi.',
            'telepon.max' => 'No HP maksimal 30 karakter.',
        ]);
    }
}
