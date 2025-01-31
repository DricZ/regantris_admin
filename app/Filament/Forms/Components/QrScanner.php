<?php

namespace App\Filament\Forms\Components;

use App\Models\Members;
use Closure;
use Filament\Forms\Components\Field;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

class QrScanner extends Field
{
    protected string $view = 'filament.forms.components.qr-scanner';

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (self $component) {
            $component->state($component->getState());
        });

        $this->afterStateUpdated(function ($state, Set $set) {
            try {
                if (empty($state)) return;

                $member = Members::where('code', $state)
                    ->firstOrFail();

                $set('member_id', $member->id);

            } catch (\Exception $e) {
                Notification::make()
                    ->title('Invalid QR Code')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        });
    }
}
