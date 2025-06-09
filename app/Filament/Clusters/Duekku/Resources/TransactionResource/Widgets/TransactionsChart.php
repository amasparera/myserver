<?php

namespace App\Filament\Clusters\Duekku\Resources\TransactionResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use Carbon\Carbon; // Pastikan Carbon di-import

class TransactionsChart extends ChartWidget
{
    protected static ?string $heading = 'Ringkasan Transaksi Bulanan';

    public ?string $filter = 'month'; // Atur filter default, misalnya 'month'

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Terakhir',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $query = Transaction::query();

        // Terapkan filter berdasarkan nilai $this->filter
        switch ($this->filter) {
            case 'today':
                $query->whereDate('date', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('date', Carbon::now()->year);
                break;
        }

        // Jika filter bukan 'month' atau 'year', kita perlu mengelompokkan berdasarkan tanggal untuk tampilan yang lebih granular.
        // Jika filter 'month' atau 'year', kelompokkan berdasarkan bulan seperti sebelumnya.
        $groupByFormat = ($this->filter === 'today' || $this->filter === 'week') ? '%Y-%m-%d' : '%Y-%m';

        $data = $query
            ->selectRaw("DATE_FORMAT(date, '{$groupByFormat}') as period,
                         SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                         SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense")
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $labels = $data->pluck('period')->map(function ($period) use ($groupByFormat) {
            if ($groupByFormat === '%Y-%m-%d') {
                return Carbon::parse($period)->translatedFormat('d M'); // Contoh: "09 Jun"
            }
            return Carbon::parse($period)->translatedFormat('F Y'); // Contoh: "Juni 2025"
        })->toArray();


        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $data->pluck('total_income')->toArray(),
                    'backgroundColor' => '#22c55e', // Warna hijau untuk pemasukan
                    'borderColor' => '#16a34a',
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $data->pluck('total_expense')->toArray(),
                    'backgroundColor' => '#ef4444', // Warna merah untuk pengeluaran
                    'borderColor' => '#dc2626',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Menggunakan grafik batang
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => <<<JS
                        function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                        JS,
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => <<<JS
                        function(context) {
                            return context.dataset.label + ": Rp " + context.parsed.y.toLocaleString("id-ID");
                        }
                        JS,
                    ],
                ],
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'weight' => 'bold',
                        ],
                    ],
                ],
                // >>>>>> Bagian ini yang perlu DITAMBAHKAN <<<<<<
                'datalabels' => [
                    'color' => '#333', // Warna teks label (gunakan warna gelap agar terlihat jelas di atas bar)
                    'anchor' => 'end', // Posisi label di akhir bar (atas)
                    'align' => 'top', // Penjajaran label ke atas bar
                    'offset' => 4, // Sedikit offset dari ujung bar
                    'formatter' => <<<JS
                        function(value, context) {
                            if (value === 0) return ''; // Jangan tampilkan 0
                            return 'Rp ' + value.toLocaleString('id-ID'); // Format nilai menjadi mata uang
                        }
                    JS,
                    'font' => [
                        'weight' => 'bold', // Tebal font label
                        'size' => 12, // Ukuran font
                    ]
                ]
                // >>>>>> Akhir dari bagian yang perlu DITAMBAHKAN <<<<<<
            ],
            'maintainAspectRatio' => false, // Memungkinkan Anda mengontrol tinggi chart dengan CSS jika diperlukan
        ];
    }
}
