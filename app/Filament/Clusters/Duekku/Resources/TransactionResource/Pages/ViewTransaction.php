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

    // before fill
    protected function beforeFill(): void
    {
        // Load related models to avoid N+1 query problem

        $this->record->load(['account', 'category']);
        //  $category = \App\Models\Category::find($state);
        // if ($category) {
        //     $set('type', $category->type);
        // }

        // Set the type based on the category type
        $this->record->type = $this->record->category->type ?? 'expense'; // Default to 'expense' if no category
    }
}
