<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembersResource\Pages;
use App\Filament\Resources\MembersResource\RelationManagers;
use App\Models\Members;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembersResource extends Resource
{
    protected static ?string $model = Members::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('nominal_room')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('nominal_resto')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('nominal_laundry')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('nominal_transport')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('nominal_spa')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('nominal_other')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_nominal')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('poin')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tier')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
            //
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
