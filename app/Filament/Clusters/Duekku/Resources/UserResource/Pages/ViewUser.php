<?php

namespace App\Filament\Clusters\Duekku\Resources\UserResource\Pages;

use App\Filament\Clusters\Duekku\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
