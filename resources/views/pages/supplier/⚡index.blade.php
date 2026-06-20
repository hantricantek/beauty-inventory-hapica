<?php
<<<<<<< Updated upstream

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Supplier;

new class extends Component
{
    // State Properti untuk Form
    public ?Supplier $supplier = null;
    public string $name = '';
    public string $phone = '';
    public string $address = '';

    #[Computed]
    public function suppliers()
    {
        return Supplier::latest()->get();
    }

    // Fungsi Buka Modal Create & Reset Form
    public function openCreateModal()
    {
        $this->reset(['name', 'phone', 'address', 'supplier']);
        $this->resetValidation();
        Flux::modal('create-supplier')->show();
    }

    // Fungsi Simpan (Create)
    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Supplier::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        Flux::modal('create-supplier')->close();
    }

    // Fungsi Buka & Load Data untuk Edit
    public function edit($id)
    {
        $this->resetValidation();
        $this->supplier = Supplier::find($id);
        $this->name = $this->supplier->name;
        $this->phone = $this->supplier->phone ?? '';
        $this->address = $this->supplier->address ?? '';

        Flux::modal('edit-supplier')->show();
    }

    // Fungsi Update Data
    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $this->supplier->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        Flux::modal('edit-supplier')->close();
    }


    public function delete($id)
    {
        $this->supplier = Supplier::find($id);
        Flux::modal('delete-supplier')->show();
    }

    public function destroy()
    {
        $this->supplier->delete();
        Flux::modal('delete-supplier')->close();
    }
};

?>

<div class="max-w-7xl mx-auto space-y-4">

    <flux:heading size="xl">
        Suppliers
    </flux:heading>

    <flux:subheading size="lg">
        Manage your suppliers
    </flux:subheading>

    <flux:separator variant="subtle" />

    <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
        Add Supplier
    </flux:button>

    <div class="overflow-x-auto">

        <flux:table>

            <flux:table.columns>

                <flux:table.column>
                    No.
                </flux:table.column>

                <flux:table.column>
                    Name
                </flux:table.column>

                <flux:table.column>
                    Phone
                </flux:table.column>

                <flux:table.column>
                    Address
                </flux:table.column>

                <flux:table.column>
                    Action
                </flux:table.column>

            </flux:table.columns>

            <flux:table.rows>

                @foreach ($this->suppliers as $supplier)

                    <flux:table.row :key="$supplier->id">

                        <flux:table.cell>
                            {{ $loop->iteration }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->phone ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->address ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell>

                            <flux:dropdown>

                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="ellipsis-horizontal"
                                />

                                <flux:menu>

                                    <flux:menu.item
                                        icon="pencil"
                                        wire:click="edit({{ $supplier->id }})"
                                    >
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item
                                        variant="danger"
                                        icon="trash"
                                        wire:click="delete({{ $supplier->id }})"
                                    >
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>

                        </flux:table.cell>

                    </flux:table.row>

                @endforeach

            </flux:table.rows>

        </flux:table>

    </div>

    <flux:modal name="create-supplier" class="md:w-150">
        <form class="space-y-6" wire:submit.prevent="save">
            
            <div>
                <flux:heading size="lg">Create Supplier</flux:heading>
                <flux:text class="text-zinc-500">Add a new supplier to your system</flux:text>
            </div>

            <div class="space-y-4">
                <flux:input label="Name" wire:model="name" placeholder="Supplier name..." />
                <flux:input label="Phone" wire:model="phone" placeholder="Phone number..." />
                <flux:textarea label="Address" wire:model="address" placeholder="Supplier address..." />
            </div>

            <div class="flex justify-end gap-3 border-t pt-4 border-zinc-200 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">Save</flux:button>
            </div>

        </form>
    </flux:modal>

    <flux:modal name="edit-supplier" class="md:w-150">
        <form class="space-y-6" wire:submit.prevent="update">
            
            <div>
                <flux:heading size="lg">Edit Supplier</flux:heading>
                <flux:text class="text-zinc-500">Update supplier details</flux:text>
            </div>

            <div class="space-y-4">
                <flux:input label="Name" wire:model="name" />
                <flux:input label="Phone" wire:model="phone" />
                <flux:textarea label="Address" wire:model="address" />
            </div>

            <div class="flex justify-end gap-3 border-t pt-4 border-zinc-200 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">Update</flux:button>
            </div>

        </form>
    </flux:modal>

    <flux:modal name="delete-supplier" class="md:w-120">
        <form class="space-y-6" wire:submit.prevent="destroy">
            
            <div>
                <flux:heading size="lg">Delete Supplier</flux:heading>
                <flux:text class="text-zinc-500">Are you sure you want to delete this supplier? This action cannot be undone.</flux:text>
            </div>

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" color="danger" type="submit">Delete</flux:button>
            </div>

        </form>    </flux:modal>

=======
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Supplier;
use App\Livewire\Forms\SupplierForm;

new class extends Component {
    use WithPagination;

    // Menggunakan Form Object yang sama untuk validasi data
    public SupplierForm $form;

    // State untuk sorting data di tabel
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Fungsi logika sorting
    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    // Mengambil data supplier secara reaktif
    #[Computed]
    public function suppliers()
    {
        return Supplier::query()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(5);
    }

    // Reset Form & Validasi Error
    public function resetForm()
    {
        $this->resetValidation();
        $this->form->reset();
    }

    // 1. Logika Aksi Edit (Mengisi data form & memunculkan modal)
    public function edit($id){
        $this->resetForm();
        $supplier = Supplier::find($id);
        $this->form->setSupplier($supplier);
        Flux::modal('edit-supplier')->show();
    }

    // 2. Logika Aksi Update Data
    public function updateSupplier() {
        $this->form->update();
        Flux::modal('edit-supplier')->close();
        $this->resetForm();
        session()->flash('success', 'Supplier updated successfully');
        $this->redirectRoute('supplier.index', navigate: true);
    }

    // 3. Logika Aksi Konfirmasi Hapus
    public function confirmDelete($id)
    {
        $supplier = Supplier::find($id);
        $this->form->setSupplier($supplier);
        Flux::modal('delete-supplier')->show();
    }

    // 4. Logika Aksi Hapus dari Database
    public function deleteSupplier() {
        $this->form->supplier->delete();
        Flux::modal('delete-supplier')->close();
        $this->resetForm();
        session()->flash('success', 'Supplier deleted successfully');
        $this->redirectRoute('supplier.index', navigate: true);
    }
};?>

<div class="max-w-7xl mx-auto space-y-4">
    <flux:heading size="xl" class="text-zinc-800 dark:text-white">Suppliers</flux:heading>
    <flux:subheading size="lg" class="text-zinc-600 dark:text-zinc-400">Manage your suppliers</flux:subheading>
    <flux:separator variant="subtle" />

    <flux:modal.trigger name="create-supplier">
        <flux:button variant="primary" icon="plus" color="primary">Add Supplier</flux:button>
    </flux:modal.trigger>

    <livewire:supplier.create />
    
    {{-- Komponen Flash Message untuk notifikasi sukses session --}}
    <x-flash-message />

    {{-- Data Tabel Supplier --}}
    <div class="overflow-x-auto">
       <flux:table :paginate="$this->suppliers">
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Address</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            
            <flux:table.rows>
                @foreach ($this->suppliers as $supplier)
                    <flux:table.row :key="$supplier->id">
                        <flux:table.cell class="font-medium text-zinc-900 dark:text-white">
                            {{ $supplier->name }}
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                            {{ $supplier->email ?? '-' }}
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                            {{ $supplier->phone ?? '-' }}
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400 max-w-xs truncate">
                            {{ $supplier->address ?? '-' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                                <flux:menu>
                                    {{-- Memanggil fungsi edit lokal langsung --}}
                                    <flux:menu.item icon="pencil" wire:click="edit({{ $supplier->id }})">Edit</flux:menu.item>
                                    <flux:menu.separator />
                                    {{-- Memanggil fungsi hapus lokal langsung --}}
                                    <flux:menu.item variant="danger" icon="trash" wire:click="confirmDelete({{ $supplier->id }})">Delete</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL EDIT SUPPLIER                        --}}
    {{-- ========================================== --}}
    <flux:modal name="edit-supplier" class="md:w-150" x-on:close="$wire.resetForm()">
        <form class="space-y-6" wire:submit.prevent="updateSupplier">
            <div class="space-y-2">
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">Edit Supplier</flux:heading>
                <flux:text class="text-zinc-500 dark:text-zinc-400">Modify supplier information details below</flux:text>
            </div>

            <div class="space-y-4">
                <flux:input label="Name" placeholder="Enter supplier name" wire:model="form.name" />
                <flux:input label="Email" type="email" placeholder="supplier@example.com" wire:model="form.email" />
                <flux:input label="Phone" placeholder="Enter phone number" wire:model="form.phone" />
                <flux:textarea label="Address" placeholder="Enter supplier address" wire:model="form.address" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="outline" color="neutral">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" color="primary" type="submit">Update</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- ========================================== --}}
    {{-- MODAL DELETE SUPPLIER                      --}}
    {{-- ========================================== --}}
    <flux:modal name="delete-supplier" class="md:w-130" x-on:close="$wire.resetForm()">
        <form class="space-y-6" wire:submit.prevent="deleteSupplier">
            <div class="space-y-2">
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">Delete Supplier</flux:heading>
                <flux:text class="text-zinc-500 dark:text-zinc-400">Are you sure you want to delete this supplier? This action cannot be undone.</flux:text>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <flux:modal.close>
                    <flux:button variant="outline" color="neutral">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" color="danger" type="submit">Delete</flux:button>
            </div>
        </form>
    </flux:modal>
>>>>>>> Stashed changes
</div>