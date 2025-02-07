<?php

namespace App\Filament\Imports;

use App\Models\Members;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class MembersImporter extends Importer
{
    protected static ?string $model = Members::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required'])
                ->label('Kode Member'),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required'])
                ->label('Email'),
            ImportColumn::make('phone_number')
                ->requiredMapping()
                ->rules(['required'])
                ->label(label: 'Phone Number'),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required'])
                ->label('Nama'),
        ];
    }

    public function resolveRecord(): ?Members
    {
        return Members::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'code' => $this->data['code'],
            'email' => $this->data['email'],
            'phone_number' => $this->data['phone_number'],
            'name' => $this->data['name']
        ]);

        // return new Members();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your members import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}