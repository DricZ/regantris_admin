<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedeemLogResource\Pages;
use App\Filament\Resources\RedeemLogResource\RelationManagers;
use App\Models\RedeemLog;
use App\Models\Transactions;
use App\Models\VoucherDetail;
use Filament\Forms;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class RedeemLogResource extends Resource
{
    protected static ?string $model = RedeemLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $uuid = Str::uuid()->toString();
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(100)
                    ->placeholder($uuid)
                    ->default($uuid),
                MorphToSelect::make('model')
                    ->label('Model Terkait')
                    ->types([
                        MorphToSelect\Type::make(Transactions::class)
                            ->titleAttribute('code') // Atribut yang akan ditampilkan di select setelah tipe dipilih
                            ->label('Transaksi')
                            // Anda bisa menambahkan search Debounce jika daftar transaksinya banyak
                            // ->searchable()
                            // ->searchDebounce(500)
                            // Anda bisa menggunakan getOptionLabelFromRecordUsing jika perlu format yang lebih kompleks
                            // ->getOptionLabelFromRecordUsing(fn (Transaction $record): string => "Transaksi ID: {$record->id} - User: {$record->user->name}")
                            ,
                        MorphToSelect\Type::make(VoucherDetail::class)
                            ->titleAttribute('code') // Misalnya, jika VoucherDetail punya atribut 'code' yang unik
                            ->label('Detail Voucher')
                            // ->getOptionLabelFromRecordUsing(fn (VoucherDetail $record): string => "Voucher Code: {$record->code}")
                    ])
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('use_poin')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('reward')
                    ->required()
                    ->prefix('Rp. ')
                    ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                    ->placeholder(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                // Kolom untuk menampilkan Tipe Parent
                Tables\Columns\TextColumn::make('model_type')
                    ->label('Tipe Terkait')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Transactions::class => 'Transaction',
                        VoucherDetail::class => 'Voucher',
                        default => $state, // Jika ada tipe lain
                    }),

                // Kolom untuk menampilkan ID Parent dan opsional detail lain
                Tables\Columns\TextColumn::make('model.id') // Mengakses relasi 'model' dan menampilkan 'id'-nya
                    ->label('ID Terkait'),
                Tables\Columns\TextColumn::make('use_poin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reward')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
            'index' => Pages\ListRedeemLogs::route('/'),
            'create' => Pages\CreateRedeemLog::route('/create'),
            'view' => Pages\ViewRedeemLog::route('/{record}'),
            'edit' => Pages\EditRedeemLog::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
