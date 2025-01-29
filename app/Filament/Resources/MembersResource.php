<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembersResource\Pages;
use App\Filament\Resources\MembersResource\RelationManagers;
use App\Models\Members;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Log;


class MembersResource extends Resource
{
    protected static ?string $model = Members::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function updateTotalNominal($set, $get): void
    {
        $total =
            (floatval(str_replace(',', '', $get('nominal_room'))) ?? 0) +
            (floatval(str_replace(',', '', $get('nominal_resto'))) ?? 0) +
            (floatval(str_replace(',', '', $get('nominal_laundry'))) ?? 0) +
            (floatval(str_replace(',', '', $get('nominal_transport'))) ?? 0) +
            (floatval(str_replace(',', '', $get('nominal_spa'))) ?? 0) +
            (floatval(str_replace(',', '', $get('nominal_other'))) ?? 0);
        $set('total_nominal', $total);
        $set('poin', floor($total / 100));

    }


    public static function form(Form $form): Form
    {

    return $form
        ->schema([
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(100)
                ->default(Str::uuid()->toString()),

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('nominal_room')
                ->label('Nominal Room')
                ->required()
                ->placeholder('0')
                ->prefix('Rp. ')
                ->live()
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_resto')
                ->label('Nominal Resto')
                ->required()
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_laundry')
                ->label('Nominal Laundry')
                ->required()
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_transport')
                ->label('Nominal Transport')
                ->required()
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_spa')
                ->label('Nominal Spa')
                ->required()
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_other')
                ->label('Nominal Other')
                ->required()
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('total_nominal')
                ->label('Total Nominal')
                ->required()
                ->readOnly()
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2),

            Forms\Components\TextInput::make('poin')
                ->label('Poin')
                ->required()
                ->readOnly()
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2),

            Forms\Components\Select::make('tier')
                ->label('Tier')
                ->options([
                    "Urban",
                    "City Slicker",
                    "Metropolis",
                    "Explorer",
                ])
                ->default(0)
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal_room')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_resto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_laundry')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_transport')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_spa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_other')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_nominal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('poin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tier'),
                Tables\Columns\TextColumn::make('transactions_count')->counts('transactions'),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMembers::route('/create'),
            'edit' => Pages\EditMembers::route('/{record}/edit'),
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
