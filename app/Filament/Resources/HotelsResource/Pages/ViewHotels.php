<?php

namespace App\Filament\Resources\HotelsResource\Pages;

use App\Filament\Resources\HotelsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHotels extends ViewRecord
{
    protected static string $resource = HotelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
