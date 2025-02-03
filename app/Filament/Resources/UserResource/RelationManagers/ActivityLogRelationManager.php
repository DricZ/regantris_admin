<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityLogRelationManager extends RelationManager
{
    protected static string $relationship = 'activity_log';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('log_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('subject_type')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('event')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('subject_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('causer_type')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('causer_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->rules(['exists:users,id']),
                Forms\Components\Textarea::make('properties')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('batch_uuid'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('User Activity Logs')
            ->columns([
                Tables\Columns\TextColumn::make('subject_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_id')
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
    }
}
