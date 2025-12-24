<?php

namespace App\Livewire\Brand;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;

class BrandCreate extends Component
{
    public $name = '';
    public $category_id = '';
    public $processing = false;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'category_id' => 'required|exists:categories,id',
    ];

    public function save()
    {
        $this->validate();
        
        $this->processing = true;
        
        Brand::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
        ]);
        
        $this->processing = false;
        $this->dispatch('brand-created');
        $this->redirect(route('brands.index'));
    }

    public function render()
    {
        // Get all categories ordered by parent_id (null first) and then name
        $categories = Category::orderByRaw('parent_id IS NULL DESC')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();
            
        return view('livewire.brand.brand-create', compact('categories'));
    }
}