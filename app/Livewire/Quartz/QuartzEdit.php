<?php

namespace App\Livewire\Quartz;

use App\Models\Quartz;
use Livewire\Component;

class QuartzEdit extends Component
{
    public Quartz $quartz;
    public $name = '';
    public $description = '';
    public $processing = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:quartzs,name,' . $this->quartz->id,
            'description' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'name.required' => 'The quartz name is required.',
        'name.unique' => 'This quartz name already exists.',
        'name.max' => 'The quartz name may not be greater than 255 characters.',
        'description.max' => 'The description may not be greater than 500 characters.',
    ];

    public function mount(Quartz $quartz)
    {
        $this->quartz = $quartz;
        $this->authorize('update', $this->quartz);
        $this->name = $quartz->name;
        $this->description = $quartz->description;
    }

    public function save()
    {
        $this->validate();
        $this->processing = true;

        try {
            $this->quartz->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Quartz type updated successfully!'
            ]);

            $this->processing = false;
        } catch (\Exception $e) {
            $this->processing = false;
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to update quartz type. Please try again.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.quartz.quartz-edit', [
            'usersCount' => $this->quartz->users()->count(),
            'bankAccountsCount' => $this->quartz->bankAccounts()->count(),
        ]);
    }
}
