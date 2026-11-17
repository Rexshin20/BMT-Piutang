<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Peminjam;
use App\Models\Piutang;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $receivables = Piutang::query()->with('peminjam')->get();

        $paidByNota = Pembayaran::query()
            ->selectRaw('nomor_nota, SUM(jumlah) as total')
            ->groupBy('nomor_nota')
            ->pluck('total', 'nomor_nota');

        $remaining = [];

        foreach ($receivables->whereNull('lunas_at')->sortBy('id') as $receivable) {
            $key = $receivable->nomor_nota ?? ('#' . $receivable->id);

            if (!array_key_exists($key, $remaining)) {
                $remaining[$key] = (int) ($paidByNota[$key] ?? 0);
            }

            $applied = min($receivable->total, $remaining[$key]);
            $receivable->dibayar = $applied;
            $receivable->sisa = $receivable->total - $applied;
            $remaining[$key] -= $applied;
        }

        $unpaid = $receivables->whereNull('lunas_at')
            ->sortByDesc(fn (Piutang $piutang) => $piutang->tanggal->format('Y-m-d') . '|' . str_pad((string) $piutang->id, 10, '0', STR_PAD_LEFT))
            ->values();

        $totalSisa = (int) $unpaid->sum('sisa');
        $lunas = $receivables->whereNotNull('lunas_at');
        $totalLunas = (int) $lunas->sum('total');
        $borrowerCount = Peminjam::count();
        $uniqueBorrowers = $unpaid->pluck('peminjam_id')->unique()->count();

        $byMonth = [];

        foreach ($receivables as $receivable) {
            $month = $receivable->tanggal->format('Y-m');
            $byMonth[$month] = ($byMonth[$month] ?? 0) + $receivable->total;
        }

        ksort($byMonth);

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $overview = [];

        foreach (array_slice($byMonth, -6, null, true) as $month => $total) {
            $overview[] = [
                'name' => $monthNames[(int) substr($month, 5, 2) - 1],
                'total' => $total,
            ];
        }

        $paymentsThisMonth = Pembayaran::query()
            ->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month);
        $paymentsThisMonthTotal = (int) (clone $paymentsThisMonth)->sum('jumlah');
        $paymentsThisMonthCount = (clone $paymentsThisMonth)->count();

        $metrics = [
            [
                'title' => 'Sisa Piutang',
                'value' => 'Rp ' . number_format($totalSisa, 0, ',', '.'),
                'change' => $unpaid->count() . ' transaksi belum lunas',
                'icon' => 'wallet',
            ],
            [
                'title' => 'Sudah Lunas',
                'value' => 'Rp ' . number_format($totalLunas, 0, ',', '.'),
                'change' => $lunas->count() . ' transaksi lunas',
                'icon' => 'circle-check',
            ],
            [
                'title' => 'Peminjam',
                'value' => (string) $borrowerCount,
                'change' => $uniqueBorrowers . ' peminjam masih punya piutang',
                'icon' => 'users',
            ],
            [
                'title' => 'Pembayaran Bulan Ini',
                'value' => 'Rp ' . number_format($paymentsThisMonthTotal, 0, ',', '.'),
                'change' => $paymentsThisMonthCount . ' kali bayar tercatat',
                'icon' => 'credit-card',
            ],
        ];

        $recentPiutang = $unpaid->take(5)->map(function (Piutang $piutang) {
            return [
                'name' => $piutang->peminjam?->nama ?? '-',
                'meta' => ($piutang->nomor_nota ? 'Nota ' . $piutang->nomor_nota . ' • ' : '')
                    . $piutang->barang . ' • ' . $piutang->tanggal->format('d M Y'),
                'amount' => 'Rp ' . number_format($piutang->sisa, 0, ',', '.'),
            ];
        })->all();

        return view('dashboard', compact(
            'metrics',
            'overview',
            'recentPiutang',
        ) + ['unpaidCount' => $unpaid->count()]);
    }
}
