<?php

namespace App\Filament\Clusters\Duekku\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetsRelationManager extends RelationManager
{
    protected static string $relationship = 'budgets';

    //id (PK)
    // user_id (FK → users.id)
    // category_id (FK → categories.id)
    // amount              -- batas maksimal
    // start_date
    // end_date
    // created_at
    // updated_at

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label('Batas Maksimal')
                    ->default(0)
                    ->required()
                    ->prefix('IDR ')
                    ->placeholder('0')
                    ->mask(RawJs::make('$money($input, \'.\', \',\', 0, \'IDR \', \'\')')) // Menggunakan $money helper
                    ->stripCharacters(',') // Hapus titik pemisah ribuan sebelum disimpan
                    ->numeric(), // Pastikan input dianggap sebagai angka

                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->label('Kategori'),
                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->label('Tanggal Mulai'),

                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->label('Tanggal Selesai'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('idr')
                    ->label('Batas Maksimal'),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->label('Tanggal Mulai'),

                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->label('Tanggal Selesai'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Dibuat Pada')
                    ->sortable(),
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
