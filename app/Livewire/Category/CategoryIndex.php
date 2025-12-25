<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryIndex extends Component
{
    use WithPagination;
    public $search = '';
    public $showDeleteModal = false;
    public $categoryIdToDelete = null;

    protected $listeners = [
        'category-created' => '$refresh',
        'category-updated' => '$refresh',
        'category-deleted' => '$refresh'
    ];

    public function confirmDelete($id)
    {
        $this->categoryIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $category = Category::findOrFail($this->categoryIdToDelete);
        $this->authorize('delete', $category);
        $category->delete();
        $this->showDeleteModal = false;
        $this->dispatch('category-deleted');
    }
    public function render()
    {
        $this->authorize('viewAny', Category::class);
        $categories = Category::with('parent')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.category.category-index', compact('categories'));
    }
}
