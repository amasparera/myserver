<?php

namespace App\Filament\Clusters\Duekku\Resources\UserResource\Pages;

use App\Filament\Clusters\Duekku\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
