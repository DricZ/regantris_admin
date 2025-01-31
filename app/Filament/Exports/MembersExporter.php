<?php

namespace App\Filament\Exports;

use App\Models\Members;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MembersExporter extends Exporter
{
    protected static ?string $model = Members::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('code')
                ->label('Kode Member'),
            ExportColumn::make('name')
                ->label('Nama'),
            ExportColumn::make('nominal_room')
                ->label('Nominal Room'),
            ExportColumn::make('nominal_resto')
                ->label('Nominal Resto'),
            ExportColumn::make('nominal_laundry')
                ->label('Nominal Laundry'),
            ExportColumn::make('nominal_transport')
                ->label('Nominal Transpor'),
            ExportColumn::make('nominal_spa')
                ->label('Nominal Spa'),
            ExportColumn::make('nominal_other')
                ->label('Nominal Other'),
            ExportColumn::make('total_nominal')
                ->label('Total Nominal'),
            ExportColumn::make('poin')
                ->label('Poin'),
            ExportColumn::make('reward')
                ->label('Reward'),
            ExportColumn::make('tier')
                ->label('Tier')
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your members export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}