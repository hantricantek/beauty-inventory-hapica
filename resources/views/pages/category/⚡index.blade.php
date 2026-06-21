<?php
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Category;

new class extends Component {
    use WithPagination;

    // Properti Filter & Sort
    #[Url(history: true)]
    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Properti Form (Dipakai bersama untuk Create & Edit)
    public $category_id = null;
    public $category_code = '';
    public $name = '';
    public $description = '';

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function resetSearch() {
        $this->reset('search');
    }

    #[Computed]
    public function categories() {
        return Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('category_code', 'like', '%' . $this->search . '%');
            })
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(10);
    }

    // --- FUNGSI CREATE ---
    public function save() {
        $this->validate([
            'category_code' => 'required|unique:categories,category_code',
            'name' => 'required|min:3',
            'description' => 'nullable',
        ]);

        Category::create([
            'category_code' => strtoupper($this->category_code),
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->resetForm();
        $this->dispatch('modal-close', name: 'create-category');
        session()->flash('success', '✓ Category created successfully!');
    }

    // --- FUNGSI EDIT ---
    public function edit($id) {
        $category = Category::findOrFail($id);
        $this->category_id = $category->id;
        $this->category_code = $category->category_code;
        $this->name = $category->name;
        $this->description = $category->description;
        
        $this->dispatch('modal-show', name: 'edit-category');
    }

    public function update() {
        $this->validate([
            'category_code' => 'required|unique:categories,category_code,' . $this->category_id,
            'name' => 'required|min:3',
            'description' => 'nullable',
        ]);

        Category::findOrFail($this->category_id)->update([
            'category_code' => strtoupper($this->category_code),
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->resetForm();
        $this->dispatch('modal-close', name: 'edit-category');
        session()->flash('success', '✓ Category updated successfully!');
    }

    // --- FUNGSI DELETE ---
    public function confirmDelete($id) {
        $this->category_id = $id;
        $this->dispatch('modal-show', name: 'delete-category');
    }

    public function delete() {
        Category::findOrFail($this->category_id)->delete();
        $this->resetForm();
        $this->dispatch('modal-close', name: 'delete-category');
        session()->flash('success', '✓ Category deleted successfully!');
    }

    public function resetForm() {
        $this->reset(['category_id', 'category_code', 'name', 'description']);
        $this->resetValidation();
    }
};?>

<div class="max-w-7xl mx-auto space-y-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-800 dark:text-white">Beauty Inventory Categories</flux:heading>
            <flux:subheading size="lg" class="text-zinc-600 dark:text-zinc-400">Manage cosmetic and skincare classifications</flux:subheading>
        </div>
        
        <flux:modal.trigger name="create-category">
            <flux:button variant="primary" icon="plus" color="emerald" wire:click="resetForm">Add New Category</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:separator variant="subtle" />

    {{-- Search Bar --}}
    <div class="flex gap-3 items-center">
        <div class="flex-1">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                icon="magnifying-glass" 
                placeholder="Search category by name or code..." 
                clearable
            />
        </div>
        <flux:button variant="outline" wire:click="resetSearch" icon="list-bullet">
            All Categories
        </flux:button>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg font-medium flex items-center gap-2 dark:bg-emerald-950/30 dark:border-emerald-800/50 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Data --}}
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow border border-zinc-200 dark:border-zinc-800">
       <flux:table :paginate="$this->categories">
            <flux:table.columns>
                <flux:table.column class="w-12">No</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'category_code'" :direction="$sortDirection" wire:click="sort('category_code')">Code</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Category Name</flux:table.column>
                <flux:table.column>Description</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created At</flux:table.column>
                <flux:table.column class="text-right">Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->categories as $index => $category)
                    <flux:table.row :key="$category->id">
                        <flux:table.cell class="text-zinc-500 text-sm font-medium">
                            {{ ($this->categories->currentPage() - 1) * $this->categories->perPage() + $index + 1 }}
                        </flux:table.cell>
                        
                        <flux:table.cell class="font-semibold text-zinc-700 dark:text-zinc-300">
                            <span class="bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded text-xs border border-zinc-300">
                                {{ $category->category_code }}
                            </span>
                        </flux:table.cell>
                        <flux:table.cell class="font-medium text-zinc-900 dark:text-white">
                            {{ $category->name }}
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                            {{ $category->description ?? '-' }}
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap text-zinc-500">{{ $category->created_at->diffForHumans() }}</flux:table.cell>
                        
                        <flux:table.cell class="text-right flex justify-end gap-2">
                            <flux:button variant="filled" size="sm" icon="pencil" color="blue" wire:click="edit({{ $category->id }})">
                                Edit
                            </flux:button>
                            
                            <flux:button variant="filled" size="sm" icon="trash" color="red" wire:click="confirmDelete({{ $category->id }})">
                                Delete
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-8 text-zinc-400">
                            No categories found. Click "All Categories" to reset.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    {{-- MODAL CREATE --}}
    <flux:modal name="create-category" class="md:w-150">
        <form class="space-y-6" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">Create New Inventory Category</flux:heading>
                <flux:text>Add segments like Skincare, Bodycare, or Fragrance.</flux:text>
            </div>

            <div class="space-y-4">
                <div>
                    <flux:input label="Category Code (e.g., SKC, MKP)" wire:model.live.debounce.250ms="category_code" />
                    @error('category_code') <span class="text-xs text-red-500 block mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div>
                    <flux:input label="Category Name" placeholder="e.g., Skincare" wire:model.live.debounce.250ms="name" />
                    @error('name') <span class="text-xs text-red-500 block mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div>
                    <flux:textarea label="Description" placeholder="Optional details..." wire:model="description" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" color="emerald" type="submit">Save Category</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- MODAL EDIT --}}
    <flux:modal name="edit-category" class="md:w-150">
        <form class="space-y-6" wire:submit.prevent="update">
            <div>
                <flux:heading size="lg">Edit Category</flux:heading>
                <flux:text>Update your cosmetic and skincare classifications.</flux:text>
            </div>

            <div class="space-y-4">
                <div>
                    <flux:input label="Category Code" wire:model.live.debounce.250ms="category_code" />
                    @error('category_code') <span class="text-xs text-red-500 block mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div>
                    <flux:input label="Category Name" wire:model.live.debounce.250ms="name" />
                    @error('name') <span class="text-xs text-red-500 block mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div>
                    <flux:textarea label="Description" wire:model="description" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" color="blue" type="submit">Update Category</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- MODAL DELETE --}}
    <flux:modal name="delete-category" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="text-red-600">Delete Category?</flux:heading>
                <flux:text>Are you sure you want to delete this category? This action cannot be undone.</flux:text>
            </div>

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" color="red" wire:click="delete">Yes, Delete</flux:button>
            </div>
        </div>
    </flux:modal>
</div>