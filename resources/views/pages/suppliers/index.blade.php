<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Supplier;

new class extends Component
{
    #[Computed]
    public function suppliers()
    {
        return Supplier::latest()->get();
    }

    public function edit($id)
    {
        $this->dispatch('edit-supplier', id: $id);
    }

    public function delete($id)
    {
        $this->dispatch('confirm-delete-supplier', id: $id);
    }
};

?>

<div class="max-w-7xl mx-auto space-y-4">

    <flux:heading size="xl">
        Supplier Management
    </flux:heading>

    <flux:subheading size="lg">
        Manage your suppliers
    </flux:subheading>

    <flux:separator variant="subtle" />

    <!-- Button Add Supplier -->
    <flux:modal.trigger name="create-supplier">
        <flux:button
            variant="primary"
            icon="plus"
        >
            Add Supplier
        </flux:button>
    </flux:modal.trigger>

    <!-- Modal Create -->
    <livewire:supplier.create />

    <!-- Modal Edit -->
    <livewire:supplier.edit />

    <div class="overflow-x-auto">

        <flux:table>

            <flux:table.columns>

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
                            {{ $supplier->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->phone }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->address }}
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

</div>