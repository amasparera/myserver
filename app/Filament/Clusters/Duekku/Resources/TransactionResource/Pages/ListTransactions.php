<?php

namespace App\Filament\Clusters\Duekku\Resources\TransactionResource\Pages;

use App\Filament\Clusters\Duekku\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // header widget
    protected function getHeaderWidgets(): array
    {
        return [
            TransactionResource\Widgets\TransactionsChart::class,
            TransactionResource\Widgets\TransactionsStatsOverview::class,
        ];
    }
}
