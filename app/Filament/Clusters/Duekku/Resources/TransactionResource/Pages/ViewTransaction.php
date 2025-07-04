<?php

namespace App\Filament\Clusters\Duekku\Resources\TransactionResource\Pages;

use App\Filament\Clusters\Duekku\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
