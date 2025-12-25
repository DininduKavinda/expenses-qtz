<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class CategoryCreate extends Component
{
    public $name;
    public $parent_id;
    public $parentCategories;
    public $processing = false;

    protected $rules = [
        'name' => 'required|min:2',
        'parent_id' => 'nullable'
    ];

    public function save()
    {
        $this->validate();

        $this->processing = true;

        Category::create([
            'name' => $this->name,
            'parent_id' => $this->parent_id
        ]);

        $this->processing = false;

        $this->dispatch('category-created');

        session()->flash('success', 'Category created!');
        return $this->redirect(route('categories.index'),  true);
    }

    public function mount()
    {
        $this->authorize('create', Category::class);
        $this->parentCategories = Category::whereNull('parent_id')->get();
    }

    public function render()
    {
        return view('livewire.category.category-create');
    }
}
