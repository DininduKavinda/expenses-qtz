<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryEdit extends Component
{
    public $category_id;
    public $category;
    public $name;
    public $parent_id = null;
    public $processing = false;
    public $showDeleteModal = false;

    // Use the $id parameter instead
    public function mount($id)
    {
        $this->category_id = $id;
        $this->category = Category::findOrFail($id);
        
        $this->name = $this->category->name;
        $this->parent_id = $this->category->parent_id;
       
    }

    // Rest of your methods remain the same...
    public function update()
    {
        $this->validate([
            'name' => 'required|min:2|max:100',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        
        $this->processing = true;
        
        $this->category->update([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
         
        ]);
        
        $this->processing = false;
        $this->redirect(route('categories.index'), true);
    }

    public function render()
    {
        $parentCategories = Category::where('id', '!=', $this->category_id)
            ->where(function($query) {
                $query->whereNull('parent_id');
            })
            ->get();
            
        return view('livewire.category.category-edit', compact('parentCategories'));
    }
}