<?php

namespace App\Filament\Clusters\Duekku\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DebtsRelationManager extends RelationManager
{
    protected static string $relationship = 'debts';

    //     id (PK)
    // user_id (FK → users.id)
    // name                -- nama pemberi/peminjam
    // amount
    // type                -- 'payable' (saya harus bayar) / 'receivable' (saya diberi pinjam)
    // status              -- 'unpaid' / 'paid'
    // description (optional)
    // due_date
    // created_at
    // updated_at

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Pemberi/Peminjam'),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->label('Jumlah Utang')
                    ->prefix('IDR ')
                    ->maxLength(15)
                    ->placeholder('0.00'),

                Forms\Components\Select::make('type')
                    ->options([
                        'payable' => 'Saya Harus Bayar',
                        'receivable' => 'Saya Diberi Pinjam',
                    ])
                    ->required()
                    ->label('Tipe Utang'),

                Forms\Components\Select::make('status')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'paid' => 'Sudah Dibayar',
                    ])
                    ->required()
                    ->label('Status Utang'),


                Forms\Components\DatePicker::make('due_date')
                    ->required()
                    ->label('Tanggal Jatuh Tempo'),

                Forms\Components\RichEditor::make('description')
                    ->nullable()->columnSpanFull()
                    ->label('Deskripsi (Opsional)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pemberi/Peminjam')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('idr')
                    ->label('Jumlah Utang'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Utang')
                    ->formatStateUsing(fn($state) => $state === 'payable' ? 'Saya Harus Bayar' : 'Saya Diberi Pinjam'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Utang')
                    ->formatStateUsing(fn($state) => $state === 'unpaid' ? 'Belum Dibayar' : 'Sudah Dibayar'),

                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->label('Tanggal Jatuh Tempo'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Dibuat Pada'),
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
