<?php

namespace App\Livewire\Role;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class RoleIndex extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $roleId;
    public $name, $slug, $description;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:roles,slug',
        'description' => 'nullable|string|max:500'
    ];

    public function updatedName($value)
    {
        if (!$this->roleId) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreateModal()
    {
        $this->reset(['name', 'slug', 'description', 'roleId']);
        $this->showCreateModal = true;
    }

    public function createRole()
    {
        $this->authorize('create', Role::class);
        $this->validate();

        Role::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'Role created successfully.');
    }

    public function editRole($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->description = $role->description;
        $this->showEditModal = true;
    }

    public function updateRole()
    {
        $role = Role::findOrFail($this->roleId);
        $this->authorize('update', $role);

        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $this->roleId,
            'description' => 'nullable|string|max:500'
        ]);

        $role->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Role updated successfully.');
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('delete', $role);

        if ($role->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role that has assigned users.');
            return;
        }

        $role->delete();
        session()->flash('message', 'Role deleted successfully.');
    }

    public function render()
    {
        $this->authorize('viewAny', Role::class);
        $roles = Role::withCount('permissions')->paginate(10);

        return view('livewire.role.role-index', [
            'roles' => $roles
        ]);
    }
}
