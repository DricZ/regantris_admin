<?php

namespace App\Filament\Imports;

use App\Models\Members;
use App\Models\Hotels;
use App\Models\Transactions;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\Rule;

class TransactionsImporter extends Importer
{
    protected static ?string $model = Transactions::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('member') // Kolom CSV untuk kode member
                ->requiredMapping()
                ->label('Kode Member')
                ->rules(['required', 'exists:members,code'])
                ->relationship(resolveUsing: 'code'),

            ImportColumn::make('hotel') // Kolom CSV untuk kode hotel
                ->requiredMapping()
                ->label('Kode Hotel')
                ->rules(['required', 'exists:hotels,code'])
                ->relationship(resolveUsing: 'code'),
            ImportColumn::make('type')
                ->rules(['required', 'string', Rule::in(['room', 'fnb', 'laundry', 'transport', 'spa', 'other'])]),
            ImportColumn::make('nominal')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Transactions
    {
        return new Transactions([
            'member_id' => $this->data['member'],
            'hotel_id' => $this->data['hotel'],
            'type' => $this->data['type'],
            'nominal' => $this->data['nominal'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your transactions import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}