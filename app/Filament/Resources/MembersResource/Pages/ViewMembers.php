<?php

namespace App\Filament\Resources\MembersResource\Pages;

use App\Filament\Resources\MembersResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMembers extends ViewRecord
{
    protected static string $resource = MembersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}