<?php

namespace App\Filament\Imports;

use App\Models\Members;
use App\Models\Hotels;
use App\Models\Transactions;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TransactionsImporter extends Importer
{
    protected static ?string $model = Transactions::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('member_code') // Kolom CSV untuk kode member
                ->requiredMapping()
                ->label('Kode Member')
                ->rules(['required', 'exists:members,code'])
                ->resolveUsing(function ($state) {
                    return Members::where('code', $state)->first()?->id;
                }),

            ImportColumn::make('hotel_code') // Kolom CSV untuk kode hotel
                ->requiredMapping()
                ->label('Kode Hotel')
                ->rules(['required', 'exists:hotels,code'])
                ->resolveUsing(function ($state) {
                    return Hotels::where('code', $state)->first()?->id;
                }),

            ImportColumn::make('type'),
            ImportColumn::make('nominal')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Transactions
    {
        // Cari member dan hotel berdasarkan kode
        $memberId = Members::where('code', $this->data['member_code'])->first()?->id;
        $hotelId = Hotels::where('code', $this->data['hotel_code'])->first()?->id;

        // Jika kode tidak valid, skip row
        if (!$memberId || !$hotelId) {
            return null;
        }

        return new Transactions([
            'member_id' => $memberId,
            'hotel_id' => $hotelId,
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