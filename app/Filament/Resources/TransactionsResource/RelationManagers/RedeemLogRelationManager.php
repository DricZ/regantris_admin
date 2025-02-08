<?php

namespace App\Filament\Resources\TransactionsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class RedeemLogRelationManager extends RelationManager
{
    protected static string $relationship = 'RedeemLog';

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
                Forms\Components\Select::make('transaction_id')
                    ->relationship('transaction', 'id')
                    ->default($this->getOwnerRecord()->id)
                    ->disabled()
                    ->required(),
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
                Tables\Columns\TextColumn::make('transaction.member.name')
                    ->numeric()
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