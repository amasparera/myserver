<?php

namespace App\Filament\Clusters\Duekku\Resources;

use App\Filament\Clusters\Duekku;
use App\Filament\Clusters\Duekku\Resources\TransactionResource\Pages;
use App\Filament\Clusters\Duekku\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Duekku::class;

    //id (PK)
    // user_id (FK → users.id)
    // account_id (FK → accounts.id)
    // category_id (FK → categories.id)
    // amount             -- nominal uang
    // type               -- enum: 'income' / 'expense' / 'transfer'
    // description (optional)
    // date               -- tanggal transaksi
    // created_at
    // updated_at

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi')->columns(2)
                    ->schema([
                        Forms\Components\Select::make('account_id')
                            ->relationship('account', 'name')
                            ->required()
                            ->label('Akun'),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->label('Kategori')->reactive()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                // Set the type based on the category type
                                $category = \App\Models\Category::find($state);
                                if ($category) {
                                    $set('type', $category->type);
                                }
                            }),
                        Forms\Components\Select::make('account_id_to')
                            ->visible(fn($get) => $get('type') === 'transfer')
                            ->relationship('account', 'name')
                            ->required()
                            ->label('Akun Tujuan'),
                        Forms\Components\Select::make('type')
                            ->hidden(fn($get) => $get('category_id') === null)
                            ->options([
                                'income' => 'Pemasukan',
                                'expense' => 'Pengeluaran',
                                'transfer' => 'Transfer',
                            ])
                            ->required()
                            ->label('Jenis Transaksi'),
                    ]),

                Forms\Components\Section::make('Rincian Transaksi')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->prefix('IDR ')
                            ->placeholder('0')
                            ->mask(RawJs::make('$money($input, \'.\', \',\', 0, \'IDR \', \'\')')) // Menggunakan $money helper
                            ->stripCharacters(',') // Hapus titik pemisah ribuan sebelum disimpan
                            ->numeric(), // Pastikan input dianggap sebagai angka
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->label('Tanggal'),
                        Forms\Components\RichEditor::make('description')->columnSpanFull()
                            ->label('Deskripsi'),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('date')
                ->label('Tanggal')
                ->sortable(),
            Tables\Columns\TextColumn::make('account.name')
                ->label('Akun'),
            Tables\Columns\TextColumn::make('category.name')
                ->label('Kategori'),
            Tables\Columns\BadgeColumn::make('category.type')
                ->colors([
                    'success' => 'income',
                    'danger' => 'expense',
                    'warning' => 'transfer',
                ])
                ->label('Jenis Transaksi'),
            Tables\Columns\TextColumn::make('amount')
                ->money('IDR', true)
                ->label('Jumlah'),
        ])
            ->defaultSort('date', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
