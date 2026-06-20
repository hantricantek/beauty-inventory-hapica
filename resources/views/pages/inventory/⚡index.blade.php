<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Inventory;

new class extends Component
{
    use WithPagination;

    public $search = '';

    public $product_name = '';

    public $stock = 0;

    public $unit = '';

    public $inventoryId = null;

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    protected function rules()
    {
        return [
            'product_name' => 'required|min:3',
            'stock' => 'required|integer|min:0',
            'unit' => 'nullable',
        ];
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
                    "%{$this->search}%"
                )
            )

            ->orderBy(
                $this->sortBy,
                $this->sortDirection
            )

            ->paginate(10);
    }

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

    public function save()
    {
        $this->validate();

        Inventory::create([
            'product_name' => $this->product_name,
            'stock' => $this->stock,
            'unit' => $this->unit,
        ]);

        $this->resetForm();

        session()->flash(
            'success',
            'Inventory berhasil ditambahkan'
        );
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);

        $this->inventoryId = $inventory->id;

        $this->product_name = $inventory->product_name;

        $this->stock = $inventory->stock;

        $this->unit = $inventory->unit;
    }

    public function update()
    {
        $this->validate();

        Inventory::findOrFail(
            $this->inventoryId
        )->update([
            'product_name' => $this->product_name,
            'stock' => $this->stock,
            'unit' => $this->unit,
        ]);

        $this->resetForm();

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

    public function resetForm()
    {
        $this->reset([
            'inventoryId',
            'product_name',
            'stock',
            'unit',
        ]);
    }
};

?>

<div class="max-w-7xl mx-auto space-y-6">

    <flux:heading size="xl">
        Inventory
    </flux:heading>

    <flux:subheading>
        Manage Inventory
    </flux:subheading>

    <flux:separator />

    <div class="flex gap-3">

        <flux:input
            wire:model.live="search"
            placeholder="Search Product..."
        />

        <flux:modal.trigger
            name="create-inventory"
        >
            <flux:button
                variant="primary"
                icon="plus"
            >
                Add Inventory
            </flux:button>
        </flux:modal.trigger>

    </div>

    @if(session('success'))

        <div class="text-green-600">

            {{ session('success') }}

        </div>

    @endif

    <flux:table :paginate="$this->inventories">

        <flux:table.columns>

            <flux:table.column
                sortable
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
                wire:click="sort('created_at')"
            >
                Created At
            </flux:table.column>

            <flux:table.column>
                Action
            </flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @foreach($this->inventories as $inventory)

                <flux:table.row>

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

                        <div class="flex gap-2">

                            <flux:modal.trigger
                                name="edit-inventory"
                            >

                                <flux:button
                                    size="sm"
                                    wire:click="edit({{ $inventory->id }})"
                                >
                                    Edit
                                </flux:button>

                            </flux:modal.trigger>

                            <flux:button
                                size="sm"
                                variant="danger"
                                wire:click="delete({{ $inventory->id }})"
                            >
                                Delete
                            </flux:button>

                        </div>

                    </flux:table.cell>

                </flux:table.row>

            @endforeach

        </flux:table.rows>

    </flux:table>

    <flux:modal
        name="create-inventory"
    >

        <div class="space-y-4">

            <flux:heading>
                Add Inventory
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
                wire:click="save"
            >
                Save
            </flux:button>

        </div>

    </flux:modal>

    <flux:modal
        name="edit-inventory"
    >

        <div class="space-y-4">

            <flux:heading>
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

</div>