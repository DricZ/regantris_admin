<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedeemLogResource\Pages;
use App\Filament\Resources\RedeemLogResource\RelationManagers;
use App\Models\RedeemLog;
use Filament\Forms;
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
                Forms\Components\Select::make('transaction_id')
                    ->relationship('transaction', 'id')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction.code')
                    ->numeric()
                    ->sortable(),
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