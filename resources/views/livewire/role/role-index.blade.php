<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Role Management</h2>
            <p class="text-gray-600">Configure system roles and their permissions</p>
        </div>
        @can('create', App\Models\Role::class)
            <button wire:click="openCreateModal"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i> Create New Role
            </button>
        @endcan
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="p-4 font-semibold text-gray-700">Name</th>
                    <th class="p-4 font-semibold text-gray-700">Slug</th>
                    <th class="p-4 font-semibold text-gray-700 text-center">Permissions</th>
                    <th class="p-4 font-semibold text-gray-700">Description</th>
                    <th class="p-4 font-semibold text-gray-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="p-4">
                            <span class="font-medium text-gray-800">{{ $role->name }}</span>
                        </td>
                        <td class="p-4 text-gray-600">
                            <code class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $role->slug }}</code>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                                {{ $role->permissions_count }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-500 text-sm">
                            {{ Str::limit($role->description, 50) }}
                        </td>
                        <td class="p-4 text-right space-x-2">
                            @can('manage-permissions')
                                <a href="{{ route('roles.permissions', $role->id) }}" wire:navigate
                                    class="text-indigo-600 hover:text-indigo-900" title="Manage Permissions">
                                    <i class="fas fa-key"></i>
                                </a>
                            @endcan

                            @can('update', $role)
                                <button wire:click="editRole({{ $role->id }})" class="text-blue-600 hover:text-blue-900"
                                    title="Edit Role">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endcan

                            @can('delete', $role)
                                <button wire:click="deleteRole({{ $role->id }})"
                                    wire:confirm="Are you sure you want to delete this role?"
                                    class="text-red-600 hover:text-red-900" title="Delete Role">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-xl font-bold text-gray-800">
                        {{ $showCreateModal ? 'Create New Role' : 'Edit Role' }}
                    </h3>
                    <button wire:click="$set('showCreateModal', false); $set('showEditModal', false)"
                        class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="{{ $showCreateModal ? 'createRole' : 'updateRole' }}" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Role Name</label>
                        <input type="text" wire:model.live="name" placeholder="e.g. Manager"
                            class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Slug (Identifier)</label>
                        <input type="text" wire:model="slug" placeholder="e.g. manager"
                            class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50"
                            {{ $showEditModal ? 'readonly' : '' }}>
                        @error('slug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" placeholder="Describe the purpose of this role..."
                            class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" wire:click="$set('showCreateModal', false); $set('showEditModal', false)"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                            {{ $showCreateModal ? 'Create Role' : 'Update Role' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>