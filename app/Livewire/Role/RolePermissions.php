<?php

namespace App\Livewire\Role;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;

class RolePermissions extends Component
{
    public Role $role;
    public $selectedPermissions = [];

    public function mount(Role $role)
    {
        $this->authorize('manage-roles');
        $this->role = $role;
        $this->selectedPermissions = $role->permissions()->pluck('id')->toArray();
    }

    public function save()
    {
        $this->authorize('manage-roles');

        $this->role->permissions()->sync($this->selectedPermissions);

        session()->flash('message', 'Permissions updated successfully.');
    }

    public function render()
    {
        $this->authorize('manage-roles');

        // Group permissions by feature for a better UI
        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('-', $permission->slug);
            // If it's like view-any-users, the feature is usually the last part(s)
            // For simplicity, we can try to guess from the slug or name
            if (count($parts) >= 2) {
                return $parts[count($parts) - 1];
            }
            return 'Other';
        });

        return view('livewire.role.role-permissions', [
            'groupedPermissions' => $permissions
        ]);
    }
}
