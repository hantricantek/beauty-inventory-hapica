<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Inventory;

new class extends Component
{
    use WithPagination;

    public $search = '';

    public $stockFilter = '';

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';
    public $inventoryId = null;

    public $product_name = '';

    public $stock = 0;

    public $unit = '';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection =
                $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function inventories()
    {
        return Inventory::query()

            ->when(
                $this->search,
                fn ($query) =>
                $query->where(
                    'product_name',
                    'like',
                    '%' . $this->search . '%'
                )
            )

            ->when(
                $this->stockFilter === 'available',
                fn ($query) =>
                $query->where('stock', '>', 0)
            )

            ->when(
                $this->stockFilter === 'empty',
                fn ($query) =>
                $query->where('stock', '=', 0)
            )

            ->orderBy(
                $this->sortBy,
                $this->sortDirection
            )

            ->paginate(10);
    }

    public function edit($id)
{
    $inventory = Inventory::findOrFail($id);

    $this->inventoryId = $inventory->id;

    $this->product_name = $inventory->product_name;

    $this->stock = $inventory->stock;

    $this->unit = $inventory->unit;
}
    public function save()
{
    $this->validate([
        'product_name' => 'required',
        'stock' => 'required|integer|min:0',
        'unit' => 'nullable',
    ]);

    Inventory::create([
        'product_name' => $this->product_name,
        'stock' => $this->stock,
        'unit' => $this->unit,
    ]);

    $this->reset([
        'product_name',
        'stock',
        'unit',
    ]);

    session()->flash(
        'success',
        'Inventory berhasil ditambahkan'
    );
}

public function update()
{
    Inventory::findOrFail(
        $this->inventoryId
    )->update([
        'product_name' => $this->product_name,
        'stock' => $this->stock,
        'unit' => $this->unit,
    ]);

    session()->flash(
        'success',
        'Inventory berhasil diupdate'
    );
}

public function delete($id)
{
    Inventory::findOrFail($id)->delete();

    session()->flash(
        'success',
        'Inventory berhasil dihapus'
    );
}
};

?>

<div class="max-w-7xl mx-auto space-y-4">

    <flux:heading size="xl">
        Inventory
    </flux:heading>

    <flux:subheading size="lg">
        Manage Inventory
    </flux:subheading>

    <flux:separator variant="subtle" />

    <div class="flex gap-3">

        <flux:input
            wire:model.live="search"
            placeholder="Search Product..."
        />

        <flux:select
            wire:model.live="stockFilter"
            placeholder="Filter Stock"
        >
            <flux:select.option value="">
                All
            </flux:select.option>

            <flux:select.option value="available">
                Available
            </flux:select.option>

            <flux:select.option value="empty">
                Empty
            </flux:select.option>
        </flux:select>

        <flux:modal.trigger name="create-inventory">
            <flux:button
                variant="primary"
                icon="plus"
            >
                Add Inventory
            </flux:button>
        </flux:modal.trigger>

    </div>

  
    <x-flash-message />

    <flux:table :paginate="$this->inventories">

        <flux:table.columns>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'product_name'"
                :direction="$sortDirection"
                wire:click="sort('product_name')"
            >
                Product
            </flux:table.column>

            <flux:table.column>
                Stock
            </flux:table.column>

            <flux:table.column>
                Unit
            </flux:table.column>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'created_at'"
                :direction="$sortDirection"
                wire:click="sort('created_at')"
            >
                Created At
            </flux:table.column>

            <flux:table.column>

            </flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @foreach ($this->inventories as $inventory)

                <flux:table.row :key="$inventory->id">

                    <flux:table.cell>
                        {{ $inventory->product_name }}
                    </flux:table.cell>

                    <flux:table.cell>

                        @if($inventory->stock > 0)

                            <flux:badge color="green">
                                {{ $inventory->stock }}
                            </flux:badge>

                        @else

                            <flux:badge color="red">
                                Out Of Stock
                            </flux:badge>

                        @endif

                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $inventory->unit }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $inventory->created_at->diffForHumans() }}
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
                                    wire:click="
                                        edit({{ $inventory->id }});
                                        $flux.modal('edit-inventory').show()
                                     "
                                >
                                    Edit
                                </flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item
                                    variant="danger"
                                    icon="trash"
                                    wire:click="delete({{ $inventory->id }})"
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
    <flux:modal name="edit-inventory">

    <div class="space-y-4">

        <flux:heading size="lg">
            Edit Inventory
        </flux:heading>

        <flux:input
            wire:model="product_name"
            label="Product Name"
        />

        <flux:input
            wire:model="stock"
            type="number"
            label="Stock"
        />

        <flux:input
            wire:model="unit"
            label="Unit"
        />

        <flux:button
            variant="primary"
            wire:click="update"
        >
            Update
        </flux:button>

    </div>

</flux:modal>

    <flux:modal name="create-inventory">

    <div class="space-y-4">

        <flux:heading size="lg">
            Add Inventory
        </flux:heading>

        <flux:input
            label="Product Name"
            wire:model="product_name"
        />

        <flux:input
            label="Stock"
            type="number"
            wire:model="stock"
        />

        <flux:input
            label="Unit"
            wire:model="unit"
        />

        <flux:button
            variant="primary"
            wire:click="save"
        >
            Save
        </flux:button>

    </div>

</flux:modal>

</div>