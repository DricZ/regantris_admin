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

class RedeemLogVRelationManager extends RelationManager
{
    protected static string $relationship = 'voucherDetailRedeemLogs';

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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Redeem Log')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model.code')
                    ->numeric()
                    ->label('Voucher Code')
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