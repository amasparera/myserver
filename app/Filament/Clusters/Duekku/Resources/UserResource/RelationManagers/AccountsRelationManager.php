<?php

namespace App\Filament\Clusters\Duekku\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;


class AccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'accounts';

    //id (PK)
    // user_id (FK → users.id)
    // name              -- contoh: "Dompet", "BNI", "Gopay"
    // initial_balance   -- saldo awal
    // created_at
    // updated_at

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),



                TextInput::make('initial_balance')
                    ->label('Saldo Awal')
                    ->default(0)
                    ->required()
                    ->prefix('IDR ')
                    ->placeholder('0')
                    ->mask(RawJs::make('$money($input, \'.\', \',\', 0, \'IDR \', \'\')')) // Menggunakan $money helper
                    ->stripCharacters(',') // Hapus titik pemisah ribuan sebelum disimpan
                    ->numeric(), // Pastikan input dianggap sebagai angka
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('initial_balance')
                    ->money('idr')
                    ->label('Saldo Awal'),
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
