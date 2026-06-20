<?php

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

</div>