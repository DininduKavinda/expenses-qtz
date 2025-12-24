<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $balance = $user->account->balance ?? 0;

        return view('livewire.dashboard', [
            'balance' => $balance
        ]);
    }
}
