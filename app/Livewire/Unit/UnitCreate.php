<?php

namespace App\Livewire\Unit;

use App\Models\Unit;
use Livewire\Component;

class UnitCreate extends Component
{
    public $name = '';
    public $description = '';
    public $processing = false;

    protected $rules = [
        'name' => 'required|min:1|max:50|unique:units,name',
        'description' => 'nullable|max:255',
    ];

    public function save()
    {
        $this->validate();
        
        $this->processing = true;
        
        Unit::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        
        $this->processing = false;
        $this->dispatch('unit-created');
        $this->redirect(route('units.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.unit.unit-create');
    }
}