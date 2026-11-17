<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Piutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        $paidByNota = Pembayaran::query()
            ->selectRaw('nomor_nota, SUM(jumlah) as total')
            ->groupBy('nomor_nota')
            ->pluck('total', 'nomor_nota');

        $rows = Piutang::query()->with('peminjam')->whereNull('lunas_at')->orderBy('id')->get();

        $groups = [];

        foreach ($rows as $row) {
            $key = $row->nomor_nota ?? ('#' . $row->id);

            if (!isset($groups[$key])) {
                $groups[$key] = (object) [
                    'key' => $key,
                    'nomor_nota' => $row->nomor_nota,
                    'peminjam' => $row->peminjam->nama,
                    'tanggal' => $row->tanggal,
                    'total' => 0,
                    'items' => [],
                ];
            }

            $groups[$key]->total += $row->total;
            $groups[$key]->items[] = (object) [
                'barang' => $row->barang,
                'unit' => $row->unit,
                'harga' => $row->harga,
                'total' => $row->total,
            ];
        }

        $notas = collect($groups)
            ->map(function ($nota) use ($paidByNota) {
                $dibayar = (int) ($paidByNota[$nota->key] ?? 0);
                $nota->dibayar = $dibayar;
                $nota->sisa = max(0, $nota->total - $dibayar);

                return $nota;
            })
            ->filter(fn ($nota) => $nota->sisa > 0)
            ->sortBy('key')
            ->values();

        return view('pembayaran', [
            'notas' => $notas,
            'payments' => Pembayaran::query()->orderByDesc('tanggal')->orderByDesc('id')->limit(100)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['jumlah' => preg_replace('/\D/', '', (string) $request->input('jumlah'))]);

        $validated = $request->validate([
            'nomor_nota' => ['required', 'string', 'max:50'],
            'tanggal' => ['required', 'date'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:150'],
        ], [
            'nomor_nota.required' => 'Nota wajib dipilih.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'jumlah.required' => 'Jumlah bayar wajib diisi.',
            'jumlah.integer' => 'Jumlah bayar harus berupa angka.',
            'jumlah.min' => 'Jumlah bayar minimal 1.',
            'keterangan.max' => 'Keterangan maksimal 150 karakter.',
        ]);

        $key = $validated['nomor_nota'];
        $query = Piutang::query()->whereNull('lunas_at');

        if (str_starts_with($key, '#')) {
            $query->where('id', (int) substr($key, 1));
        } else {
            $query->where('nomor_nota', $key);
        }

        $rows = $query->get();

        if ($rows->isEmpty()) {
            $message = 'Nota tidak ditemukan atau sudah lunas.';

            if ($request->wantsJson()) {
                return response()->json(['errors' => ['nomor_nota' => [$message]]], 422);
            }

            return back()->withInput()->withErrors(['nomor_nota' => $message]);
        }

        $total = (int) $rows->sum('total');
        $dibayar = (int) Pembayaran::query()->where('nomor_nota', $key)->sum('jumlah');
        $sisa = max(0, $total - $dibayar);

        if ($validated['jumlah'] > $sisa) {
            $message = 'Jumlah bayar melebihi sisa (Rp ' . number_format($sisa, 0, ',', '.') . ').';

            if ($request->wantsJson()) {
                return response()->json(['errors' => ['jumlah' => [$message]]], 422);
            }

            return back()->withInput()->withErrors(['jumlah' => $message]);
        }

        $lunas = $sisa - $validated['jumlah'] <= 0;

        DB::transaction(function () use ($validated, $rows, $lunas) {
            Pembayaran::create($validated);

            if ($lunas) {
                Piutang::query()
                    ->whereIn('id', $rows->pluck('id'))
                    ->whereNull('lunas_at')
                    ->update(['lunas_at' => now()]);
            }
        });

        if ($request->wantsJson()) {
            $applied = (int) Pembayaran::query()->where('nomor_nota', $key)->sum('jumlah');

            $items = $rows->sortBy('id')->map(function ($row) use (&$applied) {
                $paid = min($row->total, $applied);
                $applied -= $paid;

                return [
                    'id' => $row->id,
                    'dibayar' => $paid,
                    'sisa' => $row->total - $paid,
                ];
            })->values();

            return response()->json([
                'message' => 'Pembayaran berhasil dicatat.',
                'lunas' => $lunas,
                'jumlah' => (int) $validated['jumlah'],
                'tanggal' => \Illuminate\Support\Carbon::parse($validated['tanggal'])->format('d/m/Y'),
                'nota_key' => $key,
                'nota' => str_starts_with($key, '#') ? '(tanpa nota)' : $key,
                'nota_sisa' => max(0, $sisa - $validated['jumlah']),
                'keterangan' => $validated['keterangan'] ?? null,
                'items' => $items,
            ]);
        }

        return redirect()->route('pembayaran.index')->with('status', 'Pembayaran berhasil dicatat.');
    }
}
