<?php

namespace App\Filament\Exports;

use App\Models\Transactions;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TransactionsExporter extends Exporter
{
    protected static ?string $model = Transactions::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID Transaksi'),
            ExportColumn::make('created_at')
                ->label('Tanggal Transaksi'),
            ExportColumn::make('member.name')
                ->label('Nama Hotel'),
            ExportColumn::make('hotel.name')
                ->label('Nama Member'),
            ExportColumn::make('type')
                ->label('Type'),
            ExportColumn::make('nominal')
                ->label('Nominal'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your transactions export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}