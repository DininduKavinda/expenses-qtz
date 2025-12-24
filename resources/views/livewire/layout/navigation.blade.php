<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Authentication -->

    <button wire:click="logout" class="w-full p-4 rounded-xl bg-red-500 text-white hover:bg-red-600">
        <i class="fas fa-right-from-bracket mr-2"></i> Logout
    </button>

</div>
