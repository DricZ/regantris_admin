<?php

namespace App\Filament\Resources\PromotionalResource\Pages;

use App\Filament\Resources\PromotionalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPromotional extends EditRecord
{
    protected static string $resource = PromotionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
