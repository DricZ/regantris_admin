<?php

namespace App\Filament\Resources\RedeemLogResource\Pages;

use App\Filament\Resources\RedeemLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRedeemLog extends ViewRecord
{
    protected static string $resource = RedeemLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
