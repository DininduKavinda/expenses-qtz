<?php

namespace App\Livewire\Brand;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;

class BrandEdit extends Component
{
    public $brand_id;
    public $brand;
    public $name;
    public $category_id;
    public $processing = false;
    public $showDeleteModal = false;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'category_id' => 'required|exists:categories,id',
    ];

    public function mount($id)
    {
        $this->brand_id = $id;
        $this->brand = Brand::findOrFail($id);
        
        $this->name = $this->brand->name;
        $this->category_id = $this->brand->category_id;
    }

    public function update()
    {
        $this->validate();
        
        $this->processing = true;
        
        $this->brand->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
        ]);
        
        $this->processing = false;
        $this->dispatch('brand-updated');
        $this->redirect(route('brands.index'));
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $this->brand->delete();
        $this->showDeleteModal = false;
        $this->dispatch('brand-deleted');
        $this->redirect(route('brands.index'));
    }

    public function render()
    {
        $categories = Category::orderByRaw('parent_id IS NULL DESC')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();
            
        return view('livewire.brand.brand-edit', compact('categories'));
    }
}