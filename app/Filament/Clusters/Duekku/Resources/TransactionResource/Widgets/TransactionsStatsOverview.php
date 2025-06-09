<?php

namespace App\Filament\Clusters\Duekku\Resources\TransactionResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use App\Models\Account; // Pastikan model Account diimpor
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Concerns\HasForms;
use Filament\Forms\Components\Select;

class TransactionsStatsOverview extends BaseWidget
{
    // Tambahkan trait ini untuk mengaktifkan form pada widget
    use InteractsWithForms;
    // Properti publik untuk menyimpan nilai filter yang dipilih
    public ?string $filter = 'month'; // Filter waktu default
    public ?string $accountFilter = null; // Filter akun default (null = semua akun)

    protected function getStats(): array
    {
        // Mendapatkan tanggal awal dan akhir berdasarkan filter waktu yang dipilih
        $startDate = null;
        $endDate = null;

        switch ($this->filter) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        // Query dasar untuk transaksi
        $baseQuery = Transaction::query()
            ->when($startDate, fn($query) => $query->whereBetween('date', [$startDate, $endDate]))
            ->when($this->accountFilter, fn($query, $accountId) => $query->where('account_id', $accountId)); // Terapkan filter akun


        $totalIncome = (clone $baseQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // Mendapatkan perubahan persentase (opsional, bisa disesuaikan)
        $previousStartDate = null;
        $previousEndDate = null;

        switch ($this->filter) {
            case 'today':
                $previousStartDate = Carbon::yesterday()->startOfDay();
                $previousEndDate = Carbon::yesterday()->endOfDay();
                break;
            case 'week':
                $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
                $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'month':
                $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
                $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'year':
                $previousStartDate = Carbon::now()->subYear()->startOfYear();
                $previousEndDate = Carbon::now()->subYear()->endOfYear();
                break;
        }

        $previousIncome = 0;
        $previousExpense = 0;

        if ($previousStartDate) {
            $previousBaseQuery = Transaction::query()
                ->whereBetween('date', [$previousStartDate, $previousEndDate])
                ->when($this->accountFilter, fn($query, $accountId) => $query->where('account_id', $accountId)); // Terapkan filter akun juga untuk periode sebelumnya

            $previousIncome = (clone $previousBaseQuery)->where('type', 'income')->sum('amount');
            $previousExpense = (clone $previousBaseQuery)->where('type', 'expense')->sum('amount');
        }

        $incomeChange = 0;
        $incomeDescription = 'vs Periode Sblmnya';
        $incomeColor = 'success';
        if ($previousIncome > 0) {
            $incomeChange = round((($totalIncome - $previousIncome) / $previousIncome) * 100, 2);
            $incomeDescription = $incomeChange >= 0 ? "$incomeChange% naik" : abs($incomeChange) . "% turun";
            $incomeColor = $incomeChange >= 0 ? 'success' : 'danger';
        } else if ($totalIncome > 0) {
            $incomeDescription = "Tidak ada data sebelumnya";
            $incomeColor = 'info';
        }


        $expenseChange = 0;
        $expenseDescription = 'vs Periode Sblmnya';
        $expenseColor = 'success';
        if ($previousExpense > 0) {
            $expenseChange = round((($totalExpense - $previousExpense) / $previousExpense) * 100, 2);
            $expenseDescription = $expenseChange >= 0 ? "$expenseChange% naik" : abs($expenseChange) . "% turun";
            $expenseColor = $expenseChange >= 0 ? 'danger' : 'success'; // Pengeluaran naik = danger
        } else if ($totalExpense > 0) {
            $expenseDescription = "Tidak ada data sebelumnya";
            $expenseColor = 'info';
        }


        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description($incomeDescription)
                ->descriptionIcon($incomeChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($incomeColor),
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalExpense, 0, ',', '.'))
                ->description($expenseDescription)
                ->descriptionIcon($expenseChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($expenseColor),
            Stat::make('Saldo Bersih', 'Rp ' . number_format($netBalance, 0, ',', '.'))
                ->description('Pemasukan - Pengeluaran')
                ->descriptionIcon($netBalance >= 0 ? 'heroicon-m-wallet' : 'heroicon-m-currency-dollar')
                ->color($netBalance >= 0 ? 'success' : 'danger'),
        ];
    }

    // --- Tambahan untuk Filter Akun ---
    protected function getFormSchema(): array
    {
        return [
            // Tambahkan komponen Select untuk filter akun
            Select::make('accountFilter')
                ->label('Pilih Akun')
                ->options(
                    // Ambil daftar akun dari database
                    Account::pluck('name', 'id')->prepend('Semua Akun', '') // Tambahkan opsi 'Semua Akun'
                )
                ->placeholder('Filter Berdasarkan Akun')
                ->live() // Ini penting! Membuat widget refresh saat filter berubah
                ->default(null), // Defaultnya tidak ada filter akun
        ];
    }
    // --- Akhir Tambahan untuk Filter Akun ---

    // Filter waktu yang sudah ada
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }
}
