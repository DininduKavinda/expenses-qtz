<div class="p-6">
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('roles.index') }}" wire:navigate
            class="p-2 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-600"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manage Permissions: {{ $role->name }}</h2>
            <p class="text-gray-600">Select the actions this role is allowed to perform</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form wire:submit.prevent="save">
            <div class="p-6 space-y-8">
                @foreach ($groupedPermissions as $group => $permissions)
                    <div>
                        <h3
                            class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-50 pb-2">
                            {{ ucfirst($group) }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($permissions as $permission)
                                <label
                                    class="relative flex items-start p-4 rounded-xl border border-gray-100 cursor-pointer hover:bg-gray-50 transition group">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                            class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <span
                                            class="font-semibold text-gray-800 block group-hover:text-indigo-600 transition">{{ $permission->name }}</span>
                                        <span class="text-gray-500 text-xs">{{ $permission->description }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end sticky bottom-0">
                <button type="submit"
                    class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Save Permissions
                </button>
            </div>
        </form>
    </div>
</div>