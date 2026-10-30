<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use App\Models\Piutang;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $receivables = Piutang::query()
            ->with('peminjam')
            ->get()
            ->map(fn (Piutang $piutang) => [
                'nama' => $piutang->peminjam?->nama ?? '-',
                'tanggal' => $piutang->tanggal->format('Y-m-d'),
                'barang' => $piutang->barang,
                'harga' => $piutang->harga,
                'total' => $piutang->total,
            ])
            ->all();

        $totalPiutang = array_sum(array_column($receivables, 'total'));
        $transactions = count($receivables);
        $borrowerCount = Peminjam::count();
        $uniqueBorrowers = count(array_unique(array_column($receivables, 'nama')));
        $average = $transactions > 0 ? (int) round($totalPiutang / $transactions) : 0;

        $byMonth = [];

        foreach ($receivables as $receivable) {
            $month = substr($receivable['tanggal'], 0, 7);
            $byMonth[$month] = ($byMonth[$month] ?? 0) + $receivable['total'];
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

        $thisMonthKey = now()->format('Y-m');
        $lastMonthKey = now()->subMonthNoOverflow()->format('Y-m');
        $thisMonth = $byMonth[$thisMonthKey] ?? 0;
        $lastMonth = $byMonth[$lastMonthKey] ?? 0;
        $growth = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : null;

        $transactionsThisMonth = 0;

        foreach ($receivables as $receivable) {
            if (str_starts_with($receivable['tanggal'], $thisMonthKey)) {
                $transactionsThisMonth++;
            }
        }

        $metrics = [
            [
                'title' => 'Total Piutang',
                'value' => 'Rp ' . number_format($totalPiutang, 0, ',', '.'),
                'change' => $growth === null
                    ? 'Belum ada data bulan lalu'
                    : $this->growthLabel($growth) . ' dari bulan lalu',
                'icon' => 'wallet',
            ],
            [
                'title' => 'Peminjam',
                'value' => (string) $borrowerCount,
                'change' => $uniqueBorrowers . ' peminjam tercatat di piutang',
                'icon' => 'users',
            ],
            [
                'title' => 'Transaksi',
                'value' => (string) $transactions,
                'change' => $transactionsThisMonth . ' transaksi bulan ini',
                'icon' => 'list-todo',
            ],
            [
                'title' => 'Rata-rata Piutang',
                'value' => 'Rp ' . number_format($average, 0, ',', '.'),
                'change' => 'per transaksi',
                'icon' => 'credit-card',
            ],
        ];

        $sorted = $receivables;
        usort($sorted, fn ($a, $b) => strcmp($b['tanggal'], $a['tanggal']));

        $recentPiutang = [];

        foreach (array_slice($sorted, 0, 5) as $receivable) {
            $recentPiutang[] = [
                'name' => $receivable['nama'],
                'meta' => $receivable['barang'] . ' • ' . Carbon::parse($receivable['tanggal'])->format('d M Y'),
                'amount' => '+Rp ' . number_format($receivable['total'], 0, ',', '.'),
            ];
        }

        return view('dashboard', compact(
            'metrics',
            'overview',
            'recentPiutang',
            'transactionsThisMonth',
        ));
    }

    private function growthLabel(float $growth): string
    {
        return ($growth >= 0 ? '+' : '') . number_format($growth, 1, ',', '.') . '%';
    }
}
