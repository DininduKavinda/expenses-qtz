<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            'users' => 'User Management',
            'roles' => 'Role Management',
            'permissions' => 'Permission Management',
            'grns' => 'GRN Management',
            'banks' => 'Bank Account Management',
            'bank-transactions' => 'Bank Transaction Management',
            'items' => 'Item Management',
            'brands' => 'Brand Management',
            'categories' => 'Category Management',
            'units' => 'Unit Management',
            'shops' => 'Shop Management',
            'quartzs' => 'Quartz Management',
            'gdns' => 'GDN Management',
            'expense-splits' => 'Expense Split Management',
            'contributions' => 'Contribution Management',
            'approvals' => 'Approval Management',
            'audit-logs' => 'Audit Log Viewing',
            'scheduled-collections' => 'Scheduled Collection Management',
        ];

        $actions = [
            'view-any' => 'View List of',
            'view' => 'View Details of',
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
            'manage' => 'Full Management of',
        ];

        foreach ($features as $slug => $name) {
            foreach ($actions as $actionSlug => $actionName) {
                Permission::updateOrCreate(
                    ['slug' => "{$actionSlug}-{$slug}"],
                    [
                        'name' => "{$actionName} {$name}",
                        'description' => "Allows user to {$actionSlug} {$slug}",
                    ]
                );
            }
        }

        // Custom permissions
        $customPermissions = [
            ['name' => 'Confirm GRN', 'slug' => 'confirm-grns', 'description' => 'Can confirm GRN sessions'],
            ['name' => 'Process Payments', 'slug' => 'process-payments', 'description' => 'Can process expense payments'],
        ];

        foreach ($customPermissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        // Assign all permissions to Admin role
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(Permission::all());
        }

        // Assign specific permissions to Manager role
        $managerRole = Role::where('slug', 'manager')->first();
        if ($managerRole) {
            $managerPermissions = Permission::whereIn('slug', [
                'view-any-users',
                'view-any-grns',
                'create-grns',
                'confirm-grns',
                'view-any-banks',
                'view-any-gdns',
                'create-gdns'
            ])->get();
            $managerRole->permissions()->sync($managerPermissions);
        }
    }
}
