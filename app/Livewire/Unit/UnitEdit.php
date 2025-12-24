<?php

namespace App\Livewire\Unit;

use App\Models\Unit;
use Livewire\Component;

class UnitEdit extends Component
{
    public $unit_id;
    public $unit;
    public $name = '';
    public $description = '';
    public $processing = false;
    public $showDeleteModal = false;

    protected $rules = [
        'name' => 'required|min:1|max:50|unique:units,name,' . ',id',
        'description' => 'nullable|max:255',
    ];

    public function mount($id)
    {
        $this->unit_id = $id;
        $this->unit = Unit::findOrFail($id);
        
        $this->name = $this->unit->name;
        $this->description = $this->unit->description;
    }

    public function update()
    {
        $this->validate();
        
        $this->processing = true;
        
        $this->unit->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        
        $this->processing = false;
        $this->dispatch('unit-updated');
        $this->redirect(route('units.index'), navigate: true);
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $this->unit->delete();
        $this->showDeleteModal = false;
        $this->dispatch('unit-deleted');
        $this->redirect(route('units.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.unit.unit-edit');
    }
}