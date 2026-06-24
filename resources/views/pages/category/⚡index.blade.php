<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Category;

new class extends Component {
    use WithPagination;

    #[Url]
    public $search = '';

    public $sortBy = 'id';
    public $sortDirection = 'asc';

    public $category_id = null;
    public $name = '';
    public $description = '';

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
    public function categories()
    {
        return Category::query()
            ->when(trim($this->search), function ($query) {
                $query->where('name', 'like', '%' . trim($this->search) . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'description' => 'nullable',
        ]);

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->resetForm();
        $this->dispatch('modal-close', name: 'create-category');
        session()->flash('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->dispatch('modal-show', name: 'edit-category');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'description' => 'nullable',
        ]);

        Category::findOrFail($this->category_id)->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->resetForm();
        $this->dispatch('modal-close', name: 'edit-category');
        session()->flash('success', 'Category updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->category_id = $id;
        $this->dispatch('modal-show', name: 'delete-category');
    }

    public function delete()
    {
        Category::findOrFail($this->category_id)->delete();

        $this->resetForm();
        $this->dispatch('modal-close', name: 'delete-category');
        session()->flash('success', 'Category deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['category_id', 'name', 'description']);
        $this->resetValidation();
    }
};

?>

<div class="max-w-7xl mx-auto space-y-6 p-6">

    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">
                Beauty Inventory Categories
            </flux:heading>

            <flux:subheading size="lg">
                Manage cosmetic and skincare classifications
            </flux:subheading>
        </div>

        <flux:modal.trigger name="create-category">
            <flux:button
                variant="primary"
                icon="plus"
                color="emerald"
                wire:click="resetForm">
                Add New Category
            </flux:button>
        </flux:modal.trigger>
    </div>

    <flux:separator variant="subtle" />

    <div class="flex gap-3 items-center">
        <div class="flex-1">
            <flux:input
                wire:model.live.debounce.300ms="search"
                icon="magnifying-glass"
                placeholder="Search category..."
                clearable />
        </div>

        <flux:button
            variant="outline"
            x-on:click="$wire.set('search', ''); $wire.resetPage();">
            All Categories
        </flux:button>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-900 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow border border-zinc-200 dark:border-zinc-800 p-1">

        <flux:table :paginate="$this->categories">

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
                    Category Name
                </flux:table.column>

                <flux:table.column>
                    Description
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
                @forelse($this->categories as $category)
                    <flux:table.row :key="$category->id">
                        <flux:table.cell>
                            {{ $category->id }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $category->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $category->description ?: '-' }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $category->created_at->diffForHumans() }}
                        </flux:table.cell>

                        <flux:table.cell class="text-right">
                            <div class="flex justify-end gap-2">
                                <flux:button
                                    size="sm"
                                    color="blue"
                                    icon="pencil"
                                    wire:click="edit({{ $category->id }})">
                                    Edit
                                </flux:button>

                                <flux:button
                                    size="sm"
                                    color="red"
                                    icon="trash"
                                    wire:click="confirmDelete({{ $category->id }})">
                                    Delete
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-8">
                            No categories found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>

        </flux:table>

    </div>

    <flux:modal name="create-category" class="md:w-150">
        <form wire:submit.prevent="save" class="space-y-4">
            <flux:heading size="lg">Create Category</flux:heading>

            <flux:input
                label="Category Name"
                wire:model="name" />

            <flux:textarea
                label="Description"
                wire:model="description" />

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" color="emerald">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="edit-category" class="md:w-150">
        <form wire:submit.prevent="update" class="space-y-4">
            <flux:heading size="lg">Edit Category</flux:heading>

            <flux:input
                label="Category Name"
                wire:model="name" />

            <flux:textarea
                label="Description"
                wire:model="description" />

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" color="blue">Update</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="delete-category">
        <div class="space-y-4">
            <flux:heading size="lg">Delete Category</flux:heading>

            <flux:text>
                Are you sure you want to delete this category?
            </flux:text>

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>

                <flux:button color="red" wire:click="delete">Delete</flux:button>
            </div>
        </div>
    </flux:modal>

</div>