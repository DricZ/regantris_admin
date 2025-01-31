<?php

namespace App\Livewire;

// app/Livewire/QrScanner.php
use Livewire\Component;
use App\Models\Members;

class QrScanner extends Component
{
    public $memberId;
    public $showScanner = false;

    public function render()
    {
        return view('livewire.qr-scanner');
    }

    public function validateCode($code)
    {
        $member = Members::where('code', $code)->first();

        if($member) {
            $this->memberId = $member->id;
            $this->showScanner = false;
            $this->dispatch('memberScanned', $member->id);
        }
    }
}
