<?php

namespace App\Filament\Clusters\Duekku\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'categories';

    //id (PK)
    // user_id (FK → users.id)
    // name               -- contoh: "Makanan", "Transportasi"
    // type               -- enum: 'income' / 'expense'
    // icon (optional)    -- bisa berupa nama ikon
    // created_at
    // updated_at

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->options([
                        'income' => 'Pendapatan',
                        'expense' => 'Pengeluaran',
                        'save' => 'Tabungan',
                    ])
                    ->required()
                    ->label('Tipe Kategori'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Kategori')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'income' => 'Pendapatan',
                        'expense' => 'Pengeluaran',
                        'save' => 'Tabungan',
                        default => $state,
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
