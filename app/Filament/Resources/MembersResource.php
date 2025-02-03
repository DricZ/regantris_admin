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
use App\Filament\Exports\MembersExporter;
use Filament\Tables\Actions\ExportAction;

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

        $tier = self::$model::getTierOptions($total);

        $point = $total / 100000;
        $set('poin', floor($point));
        $set('reward', ceil($point * 100));
        $set('tier', $tier['name'] ?? "Urban");
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
                ->placeholder('0')
                ->prefix('Rp. ')
                ->live()
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_resto')
                ->label('Nominal Resto')
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_laundry')
                ->label('Nominal Laundry')
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_transport')
                ->label('Nominal Transport')
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_spa')
                ->label('Nominal Spa')
                ->placeholder('0')
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->live()
                ->afterStateUpdated(function (Callable $get, Set $set) {
                    Self::updateTotalNominal($set, $get);
                }),

            Forms\Components\TextInput::make('nominal_other')
                ->label('Nominal Other')
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
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2),

            Forms\Components\TextInput::make(name: 'reward')
                ->label(label: 'reward')
                ->required()
                ->readOnly()
                ->prefix('Rp. ')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2),

            Forms\Components\Select::make('tier')
                ->label('Tier')
                ->options([
                    "Urban" => "Urban",
                    "City Slicker" => "City Slicker",
                    "Metropolis" => "Metropolis",
                    "Explorer" => "Explorer",
                ])
                ->default("Urban")
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
                    ->prefix('Rp. ')
                    ->default(0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_resto')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->default(0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_laundry')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->default(0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_transport')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->default(0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_spa')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->default(0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal_other')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->default(0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_nominal')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->default(0)
                    ->sortable(),
                    Tables\Columns\TextColumn::make('poin')
                    ->default(0)
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('reward')
                    ->default(0)
                    ->prefix('Rp. ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tier')
                    ->sortable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(MembersExporter::class)
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
            'view' => Pages\ViewMembers::route('/{record}'),
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