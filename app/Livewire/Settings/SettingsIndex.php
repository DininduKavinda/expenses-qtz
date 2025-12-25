<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\LogReader;
use Illuminate\Support\Facades\Gate;

class SettingsIndex extends Component
{
    use WithPagination;

    public $activeTab = 'activity';

    // Filters
    public $dateFrom;
    public $dateTo;
    public $selectedUser;
    public $selectedType;
    public $searchQuery;
    public $selectedModule;

    protected $queryString = ['activeTab', 'selectedUser', 'selectedType', 'selectedModule'];

    public function mount()
    {
        Gate::authorize('manage-settings'); // We'll add this permission
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.settings.settings-index', [
            'logs' => $this->getLogs(),
            'users' => User::all(),
            'appErrors' => $this->activeTab === 'errors' ? LogReader::getLogs($this->getPage()) : [],
        ]);
    }

    protected function getLogs()
    {
        if ($this->activeTab === 'errors') return collect();

        $query = AuditLog::with('user')
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);

        if ($this->activeTab === 'activity') {
            $query->whereIn('type', ['crud', 'auth', 'system']);
        } elseif ($this->activeTab === 'commands') {
            $query->where('type', 'command');
        }

        if ($this->selectedUser) {
            $query->where('user_id', $this->selectedUser);
        }

        if ($this->selectedType) {
            $query->where('type', $this->selectedType);
        }

        if ($this->selectedModule) {
            $query->where('table_name', $this->selectedModule);
        }

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('action', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('table_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('metadata', 'like', '%' . $this->searchQuery . '%');
            });
        }

        return $query->latest()->paginate(20);
    }
}
