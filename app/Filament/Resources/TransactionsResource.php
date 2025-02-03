<?php

namespace App\Filament\Resources;

use App\Filament\Exports\TransactionsExporter;
use App\Filament\Resources\TransactionsResource\Pages;
use App\Filament\Resources\TransactionsResource\RelationManagers;
use App\Models\Transactions;
use App\Models\Members;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Forms\Components\QrScanner;
use Filament\Forms\Set;
use Filament\Tables\Actions\ExportAction;

class TransactionsResource extends Resource
{
    protected static ?string $model = Transactions::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                QrScanner::make('member_code')
                    ->label('Scan Member QR Code')
                    ->reactive()
                    ->dehydrated(false)
                    ->afterStateUpdated(function ($state, Set $set) {
                        $member = Members::where('code', $state)->first();
                        if ($member) {
                            $set('member_id', $member->id);
                        }
                    })
                    ->rules(['exists:members,code'])
                    ->validationMessages([
                        'exists' => 'Invalid QR code. Member not found.',
                    ]),

                Forms\Components\Select::make('member_id')
                    ->required()
                    ->relationship('member', 'name')
                    ->rules(['exists:members,id']),
                Forms\Components\Select::make('hotel_id')
                    ->relationship('hotel', 'name')
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

    public static function table(Table $table): Table
    {
        return $table
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
                    ->exporter(TransactionsExporter::class)
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransactions::route('/create'),
            'view' => Pages\ViewTransactions::route('/{record}'),
            'edit' => Pages\EditTransactions::route('/{record}/edit'),
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