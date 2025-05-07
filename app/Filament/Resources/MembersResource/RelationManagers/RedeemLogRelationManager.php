<?php

namespace App\Filament\Resources\MembersResource\RelationManagers;

use App\Models\Transactions;
use App\Models\VoucherDetail;
use Filament\Forms;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class RedeemLogRelationManager extends RelationManager
{
    protected static string $relationship = 'transactionRedeemLogs';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Redeem Log')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model.code')
                    ->numeric()
                    ->label('Transaction Code')
                    ->sortable(),
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
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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