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
use App\Filament\Imports\TransactionsImporter;
use Filament\Forms\Set;
use Filament\Tables\Actions\ExportAction;
use League\Flysystem\Visibility;

class TransactionsResource extends Resource
{
    protected static ?string $model = Transactions::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        // Cek apakah user memiliki permission untuk memilih hotel dan type
        $canSelect = auth()->user()->can('transaction.select.hotel_and_type') || auth()->user()->roles()->first()->type == 'super';

        // Jika user tidak memiliki permission untuk memilih type, ambil nilai dari kolom type di role.
        // Asumsi user hanya memiliki satu role.
        $defaultType = null;
        if (!$canSelect) {
            $role = auth()->user()->roles()->first();
            // Pastikan role ada dan kolom 'type' tersedia di tabel role (misal sudah ditambahkan kolom custom)
            $defaultType = $role ? $role->type : null;
        }

        return $form
            ->schema([
                QrScanner::make('member_id')
                    ->label('Scan Member QR Code')
                    ->reactive()
                    ->dehydrated(false)
                    ->hiddenOn('view') // This field will be hidden on view pages.
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
                    ->label('Phone Number')
                    ->relationship('member', 'phone_number')
                    ->rules(['exists:members,id'])
                    // ->disabled(!$canSelect)
                    ->searchable(),
                Forms\Components\Select::make('member_id')
                    ->required()
                    ->relationship('member', 'name')
                    ->disabled(),
                // Field Hotel: jika user tidak punya permission, default ambil hotel_id dari user dan disable input-nya.
                Forms\Components\Select::make('hotel_id')
                    ->relationship('hotel', 'name')
                    ->required()
                    ->default(fn() => !$canSelect ? auth()->user()->hotel_id : null)
                    ->disabled(fn() => !$canSelect)
                    ->visible(fn() => $canSelect),
                Forms\Components\Select::make('type')
                    ->options([
                        'room' => 'Room',
                        'fnb' => 'Resto',
                        'laundry' => 'Laundry',
                        'transport' => 'Transport',
                        'spa' => 'Spa',
                        'other' => 'Other',
                    ])
                    ->required()
                    ->default(fn() => !$canSelect ? $defaultType : 'other')
                    ->disabled(fn() => !$canSelect)
                    ->visible(fn() => $canSelect),
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
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable(),
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
                    ->exporter(TransactionsExporter::class),
                Tables\Actions\ImportAction::make()
                    ->label('Import Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->importer(TransactionsImporter::class),
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
            RelationManagers\RedeemLogRelationManager::class,
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
