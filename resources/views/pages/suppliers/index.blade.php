<div class="max-w-7xl mx-auto space-y-4">

    <!-- HEADER -->
    <flux:heading size="xl">
        Supplier Management
    </flux:heading>

    <flux:subheading size="lg">
        Manage your suppliers
    </flux:subheading>

    <flux:separator variant="subtle" />

    <!-- ADD BUTTON -->
    <flux:modal.trigger name="create-supplier">
        <flux:button variant="primary" icon="plus">
            Add Supplier
        </flux:button>
    </flux:modal.trigger>

    <!-- LIVEWIRE MODALS -->
    <livewire:suppliers.create-supplier />
    <livewire:suppliers.edit-supplier />

    <!-- TABLE -->
    <div class="overflow-x-auto">

        <flux:table>

            <!-- HEADER TABLE -->
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Address</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>

            <!-- BODY TABLE -->
            <flux:table.rows>

                @forelse ($this->suppliers as $supplier)

                    <flux:table.row>

                        <flux:table.cell>
                            {{ $supplier->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->phone }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->address }}
                        </flux:table.cell>

                        <flux:table.cell class="flex gap-2">

                            <!-- EDIT BUTTON -->
                            <flux:button size="sm"
                                wire:click="edit({{ $supplier->id }})">
                                Edit
                            </flux:button>

                            <!-- DELETE BUTTON -->
                            <flux:button size="sm" variant="danger"
                                wire:click="delete({{ $supplier->id }})"
                                onclick="confirm('Yakin mau hapus supplier ini?') || event.stopImmediatePropagation()">
                                Delete
                            </flux:button>

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center text-gray-500">
                            No suppliers found
                        </flux:table.cell>
                    </flux:table.row>

                @endforelse

            </flux:table.rows>

        </flux:table>

    </div>

</div>