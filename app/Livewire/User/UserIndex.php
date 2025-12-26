<?php

namespace App\Livewire\User;

use App\Models\Quartz;
use App\Models\User;
use App\Models\Role;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class UserIndex extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $userId;
    public $name, $email, $role_id, $quartz_id, $password, $active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'role_id' => 'required|exists:roles,id',
        'quartz_id' => 'required|exists:quartzs,id',
        'password' => 'required|min:1',
        'active' => 'boolean'
    ];

    public function toggleActive($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('toggleActive', $user);

        $user->active = !$user->active;
        $user->save();
    }

    public function openCreateModal()
    {
        $this->reset(['name', 'email', 'role_id', 'quartz_id', 'password', 'active', 'userId']);
        $this->active = true;
        $this->showCreateModal = true;
    }

    public function createUser()
    {
        $this->authorize('create', User::class);
        $this->validate();

        \Illuminate\Support\Facades\DB::transaction(function () {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
                'quartz_id' => $this->quartz_id,
                'active' => $this->active
            ]);

            // Ensure UserAccount exists
            UserAccount::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        });

        $this->showCreateModal = false;
        session()->flash('message', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->quartz_id = $user->quartz_id;
        $this->active = $user->active;
        $this->showEditModal = true;
    }

    public function updateUser()
    {
        $user = User::findOrFail($this->userId);
        $this->authorize('update', $user);

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'role_id' => 'required|exists:roles,id',
            'quartz_id' => 'required|exists:quartzs,id',
            'active' => 'boolean'
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'quartz_id' => $this->quartz_id,
            'active' => $this->active
        ]);

        if ($this->password) {
            $user->update(['password' => Hash::make($this->password)]);
        }

        $this->showEditModal = false;
        session()->flash('message', 'User updated successfully.');
    }

    public function render()
    {
        $this->authorize('viewAny', User::class);

        if (Auth::user()->role_id == 1) {
            $users = User::with('role')->paginate(10);
        } else {
            $users = User::where('quartz_id', auth()->user()->quartz_id)
                ->with('role')
                ->paginate(10);
        }

        $roles = Role::all();
        $quartzs = Quartz::all();

        return view('livewire.user.user-index', [
            'users' => $users,
            'roles' => $roles,
            'quartzs' => $quartzs
        ]);
    }
}
