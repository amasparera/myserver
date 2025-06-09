<?php

namespace App\Filament\Clusters\Duekku\Resources\TransactionResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use Carbon\Carbon; // Carbon masih berguna untuk formatting atau jika Anda ingin menambahkan logika tanggal manual nanti

class TransactionsStatsOverview extends BaseWidget
{
    // Hapus properti $filter dan $accountFilter karena tidak ada lagi filter

    protected function getStats(): array
    {
        // Query untuk menghitung total pemasukan dari SEMUA transaksi
        $totalIncome = Transaction::where('type', 'income')->sum('amount');

        // Query untuk menghitung total pengeluaran dari SEMUA transaksi
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');

        // Saldo bersih dari SEMUA transaksi
        $netBalance = $totalIncome - $totalExpense;

        // --- Bagian perubahan persentase juga bisa dihapus jika tidak ada periode pembanding ---
        // Jika Anda ingin data ini mencerminkan "semua waktu", perbandingan persentase akan tidak relevan
        // atau memerlukan logika yang sangat berbeda (misalnya, pertumbuhan bulanan/tahunan secara total).
        // Untuk penyederhanaan maksimal, saya akan menghapus logika perbandingan persentase.
        // Jika Anda ingin tetap ada perbandingan, Anda harus mendefinisikan periode "sebelumnya" secara eksplisit
        // atau kembali menggunakan filter tanggal.

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Sepanjang Waktu') // Ubah deskripsi
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Ikon tetap
                ->color('success'), // Warna tetap

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalExpense, 0, ',', '.'))
                ->description('Sepanjang Waktu') // Ubah deskripsi
                ->descriptionIcon('heroicon-m-arrow-trending-down') // Ikon tetap
                ->color('danger'), // Warna tetap

            Stat::make('Saldo Bersih', 'Rp ' . number_format($netBalance, 0, ',', '.'))
                ->description('Total Saldo Tersedia') // Ubah deskripsi
                ->descriptionIcon($netBalance >= 0 ? 'heroicon-m-wallet' : 'heroicon-m-currency-dollar')
                ->color($netBalance >= 0 ? 'success' : 'danger'),
        ];
    }
}
