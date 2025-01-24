<?php

namespace App\Filament\Resources\HotelsResource\Pages;

use App\Filament\Resources\HotelsResource;
use Filament\Resources\Pages\Page;

class Settings extends Page
{
    protected static string $resource = HotelsResource::class;

    protected static string $view = 'filament.resources.hotels-resource.pages.settings';
}
