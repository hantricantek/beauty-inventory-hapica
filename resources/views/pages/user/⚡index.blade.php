<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    use WithPagination;

    #[Url]
    public $search = '';

    public $sortBy = 'id';
    public $sortDirection = 'asc';

    public $user_id = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'staff';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->when(trim($this->search), function ($query) {
                $query->where('name', 'like', '%' . trim($this->search) . '%')
                    ->orWhere('email', 'like', '%' . trim($this->search) . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->resetForm();
        $this->dispatch('modal-close', name: 'create-user');

        session()->flash('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';

        $this->dispatch('modal-show', name: 'edit-user');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'role' => 'required',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::findOrFail($this->user_id)->update($data);

        $this->resetForm();
        $this->dispatch('modal-close', name: 'edit-user');

        session()->flash('success', 'User updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->user_id = $id;
        $this->dispatch('modal-show', name: 'delete-user');
    }

    public function delete()
    {
        User::findOrFail($this->user_id)->delete();

        $this->resetForm();
        $this->dispatch('modal-close', name: 'delete-user');

        session()->flash('success', 'User deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'user_id',
            'name',
            'email',
            'password',
            'role'
        ]);

        $this->role = 'staff';

        $this->resetValidation();
    }
};

?>

<div class="max-w-7xl mx-auto space-y-6 p-6">

    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">
                User Management
            </flux:heading>

            <flux:subheading size="lg">
                Manage application users and roles
            </flux:subheading>
        </div>

        <flux:modal.trigger name="create-user">
            <flux:button
                variant="primary"
                icon="plus"
                color="emerald"
                wire:click="resetForm">
                Add New User
            </flux:button>
        </flux:modal.trigger>
    </div>

    <flux:separator variant="subtle" />

    <div class="flex gap-3 items-center">
        <div class="flex-1">
            <flux:input
                wire:model.live.debounce.300ms="search"
                icon="magnifying-glass"
                placeholder="Search user..."
                clearable />
        </div>

        <flux:button
            variant="outline"
            x-on:click="$wire.set('search', ''); $wire.resetPage();">
            All Users
        </flux:button>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-900 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow border border-zinc-200 dark:border-zinc-800 p-1">

        <flux:table :paginate="$this->users">

            <flux:table.columns>

                <flux:table.column sortable
                    :sorted="$sortBy === 'id'"
                    :direction="$sortDirection"
                    wire:click="sort('id')">
                    ID
                </flux:table.column>

                <flux:table.column sortable
                    :sorted="$sortBy === 'name'"
                    :direction="$sortDirection"
                    wire:click="sort('name')">
                    Name
                </flux:table.column>

                <flux:table.column>
                    Email
                </flux:table.column>

                <flux:table.column>
                    Role
                </flux:table.column>

                <flux:table.column sortable
                    :sorted="$sortBy === 'created_at'"
                    :direction="$sortDirection"
                    wire:click="sort('created_at')">
                    Created At
                </flux:table.column>

                <flux:table.column class="text-right">
                    Actions
                </flux:table.column>

            </flux:table.columns>

            <flux:table.rows>

                @forelse($this->users as $user)

                    <flux:table.row :key="$user->id">

                        <flux:table.cell>
                            {{ $user->id }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $user->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $user->email }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ ucfirst($user->role) }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $user->created_at->diffForHumans() }}
                        </flux:table.cell>

                        <flux:table.cell class="text-right">
                            <div class="flex justify-end gap-2">

                                <flux:button
                                    size="sm"
                                    color="blue"
                                    icon="pencil"
                                    wire:click="edit({{ $user->id }})">
                                    Edit
                                </flux:button>

                                <flux:button
                                    size="sm"
                                    color="red"
                                    icon="trash"
                                    wire:click="confirmDelete({{ $user->id }})">
                                    Delete
                                </flux:button>

                            </div>
                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-8">
                            No users found.
                        </flux:table.cell>
                    </flux:table.row>

                @endforelse

            </flux:table.rows>

        </flux:table>

    </div>

    <flux:modal name="create-user" class="md:w-150">

        <form wire:submit.prevent="save" class="space-y-4">

            <flux:heading size="lg">
                Create User
            </flux:heading>

            <flux:input
                label="Name"
                wire:model="name" />

            <flux:input
                label="Email"
                type="email"
                wire:model="email" />

            <flux:input
                label="Password"
                type="password"
                wire:model="password" />

            <flux:select
                label="Role"
                wire:model="role">

                <option value="admin">Admin</option>
                <option value="staff">Staff</option>

            </flux:select>

            <div class="flex justify-end gap-3">

                <flux:modal.close>
                    <flux:button variant="outline">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button type="submit" color="emerald">
                    Save
                </flux:button>

            </div>

        </form>

    </flux:modal>

    <flux:modal name="edit-user" class="md:w-150">

        <form wire:submit.prevent="update" class="space-y-4">

            <flux:heading size="lg">
                Edit User
            </flux:heading>

            <flux:input
                label="Name"
                wire:model="name" />

            <flux:input
                label="Email"
                type="email"
                wire:model="email" />

            <flux:input
                label="New Password (Optional)"
                type="password"
                wire:model="password" />

            <flux:select
                label="Role"
                wire:model="role">

                <option value="admin">Admin</option>
                <option value="staff">Staff</option>

            </flux:select>

            <div class="flex justify-end gap-3">

                <flux:modal.close>
                    <flux:button variant="outline">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button type="submit" color="blue">
                    Update
                </flux:button>

            </div>

        </form>

    </flux:modal>

    <flux:modal name="delete-user">

        <div class="space-y-4">

            <flux:heading size="lg">
                Delete User
            </flux:heading>

            <flux:text>
                Are you sure you want to delete this user?
            </flux:text>

            <div class="flex justify-end gap-3">

                <flux:modal.close>
                    <flux:button variant="outline">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    color="red"
                    wire:click="delete">
                    Delete
                </flux:button>

            </div>

        </div>

    </flux:modal>

</div>