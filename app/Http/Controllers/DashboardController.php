<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use App\Support\DemoData;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $receivables = DemoData::receivables();

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
                'initials' => $this->initials($receivable['nama']),
                'name' => $receivable['nama'],
                'meta' => $receivable['barang'] . ' • ' . Carbon::parse($receivable['tanggal'])->format('d M Y'),
                'amount' => '+Rp ' . number_format($receivable['total'], 0, ',', '.'),
            ];
        }

        $daily = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $daily[$date->format('Y-m-d')] = [
                'name' => $this->dayName($date),
                'total' => 0,
            ];
        }

        foreach ($receivables as $receivable) {
            if (isset($daily[$receivable['tanggal']])) {
                $daily[$receivable['tanggal']]['total'] += $receivable['total'];
            }
        }

        $daily = array_values($daily);

        $weekStart = now()->subDays(6)->format('Y-m-d');
        $weekTotal = array_sum(array_column($daily, 'total'));
        $weekTransactions = 0;

        foreach ($receivables as $receivable) {
            if ($receivable['tanggal'] >= $weekStart) {
                $weekTransactions++;
            }
        }

        $analyticsMetrics = [
            [
                'title' => 'Piutang 7 Hari',
                'value' => 'Rp ' . number_format($weekTotal, 0, ',', '.'),
                'change' => $weekTransactions . ' transaksi minggu ini',
                'icon' => 'arrow-up',
            ],
            [
                'title' => 'Rata-rata Harian',
                'value' => 'Rp ' . number_format((int) round($weekTotal / 7), 0, ',', '.'),
                'change' => 'dari 7 hari terakhir',
                'icon' => 'timer',
            ],
            [
                'title' => 'Peminjam Aktif',
                'value' => (string) $uniqueBorrowers,
                'change' => 'dari ' . $borrowerCount . ' peminjam terdaftar',
                'icon' => 'users',
            ],
            [
                'title' => 'Jenis Barang',
                'value' => (string) count(array_unique(array_column($receivables, 'barang'))),
                'change' => 'barang tercatat',
                'icon' => 'package',
            ],
        ];

        $byBorrower = [];

        foreach ($receivables as $receivable) {
            $byBorrower[$receivable['nama']] = ($byBorrower[$receivable['nama']] ?? 0) + $receivable['total'];
        }

        arsort($byBorrower);

        $topBorrowers = [];

        foreach (array_slice($byBorrower, 0, 5, true) as $name => $value) {
            $topBorrowers[] = ['name' => $name, 'value' => $value];
        }

        $byItem = [];

        foreach ($receivables as $receivable) {
            $byItem[$receivable['barang']] = ($byItem[$receivable['barang']] ?? 0) + $receivable['total'];
        }

        arsort($byItem);

        $topItems = [];

        foreach (array_slice($byItem, 0, 5, true) as $name => $value) {
            $topItems[] = [
                'name' => $name,
                'percent' => $totalPiutang > 0 ? round(($value / $totalPiutang) * 100) : 0,
            ];
        }

        return view('dashboard', compact(
            'metrics',
            'overview',
            'recentPiutang',
            'transactionsThisMonth',
            'analyticsMetrics',
            'daily',
            'topBorrowers',
            'topItems',
        ));
    }

    private function growthLabel(float $growth): string
    {
        return ($growth >= 0 ? '+' : '') . number_format($growth, 1, ',', '.') . '%';
    }

    private function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));

        if (count($words) === 1) {
            return strtoupper(mb_substr($words[0], 0, 2));
        }

        return strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[count($words) - 1], 0, 1));
    }

    private function dayName(Carbon $date): string
    {
        return ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'][$date->dayOfWeek];
    }
}
