<?php

namespace App\Filament\Resources\RedeemLogResource\Pages;

use App\Filament\Resources\RedeemLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRedeemLog extends EditRecord
{
    protected static string $resource = RedeemLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
