<?php

namespace App\Filament\Clusters\Duekku\Resources\UserResource\Pages;

use App\Filament\Clusters\Duekku\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
