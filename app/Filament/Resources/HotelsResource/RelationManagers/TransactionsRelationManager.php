<?php

namespace App\Filament\Resources\HotelsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('member_id')
                ->relationship('member', 'name')
                ->required(),
            Forms\Components\Select::make('hotel_id')
                ->relationship('hotel', 'name')
                ->default($this->getOwnerRecord()->id)
                ->disabled()
                ->required(),
            Forms\Components\Select::make('type')
                ->options([
                    'room' => 'Room',
                    'fnb' => 'Resto',
                    'laundry' => 'Laundry',
                    'transport' => 'Transport',
                    'spa' => 'Spa',
                    'other' => 'Other',
                ])
                ->required(),
            Forms\Components\TextInput::make('nominal')
                ->required()
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->placeholder(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Hotel Transactions')
            ->columns([
                Tables\Columns\TextColumn::make('member.name')
                    ->label('Member')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('hotel.name')
                    ->label('Hotel')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('nominal')
                    ->prefix('Rp. ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
