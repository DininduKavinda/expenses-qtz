<?php

namespace App\Livewire\Quartz;

use App\Models\Quartz;
use Livewire\Component;

class QuartzCreate extends Component
{
    public function mount()
    {
        $this->authorize('create', Quartz::class);
    }

    public $name = '';
    public $description = '';
    public $processing = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:quartzs,name',
        'description' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'name.required' => 'The quartz name is required.',
        'name.unique' => 'This quartz name already exists.',
        'name.max' => 'The quartz name may not be greater than 255 characters.',
        'description.max' => 'The description may not be greater than 500 characters.',
    ];

    public function save()
    {
        $this->validate();

        $this->processing = true;

        try {
            $quartz = Quartz::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            // Auto-create Main Bank Account
            \App\Models\BankAccount::create([
                'quartz_id' => $quartz->id,
                'name' => 'Main Bank Account',
                'balance' => 0
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Quartz type created successfully!'
            ]);

            $this->processing = false;
            $this->redirect(route('quartzs.index'), navigate: true);
        } catch (\Exception $e) {
            $this->processing = false;
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to create quartz type. Please try again.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.quartz.quartz-create');
    }
}
