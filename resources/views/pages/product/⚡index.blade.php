<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Product;

new class extends Component
{
    public string $search = '';
    public string $category = '';

    #[Computed]
    public function products()
    {
        return Product::query()

            ->when(
                $this->search,
                fn ($query) =>
                    $query->where(
                        'name',
                        'like',
                        '%' . $this->search . '%'
                    )
            )

            ->when(
                $this->category,
                fn ($query) =>
                    $query->where(
                        'category_id',
                        $this->category
                    )
            )

            ->latest()
            ->get();
    }

    public function edit($id)
    {
        $this->dispatch('edit-product', id: $id);
    }

    public function delete($id)
    {
        $this->dispatch('confirm-delete', id: $id);
    }
};

?>

<div class="max-w-7xl mx-auto space-y-4">

    <flux:heading size="xl">
        Beauty Inventory
    </flux:heading>

    <flux:subheading size="lg">
        Manage your beauty products
    </flux:subheading>

    <flux:separator variant="subtle" />

@if (session()->has('success'))

    <div class="p-3 mb-4 rounded-lg bg-green-100 text-green-700">
        {{ session('success') }}
    </div>

@endif

<!-- Add Product -->
<flux:modal.trigger name="create-product">
    <flux:button
        variant="primary"
        icon="plus"
    >
        Add Product
    </flux:button>
</flux:modal.trigger>
   

    <!-- Modal Create -->
    <livewire:product.create />

    <!-- Modal Edit & Delete -->
    <livewire:product.edit />

    <!-- Search & Filter -->
    <div class="flex gap-4 mb-4">

        <flux:input
            wire:model.live="search"
            placeholder="Search product..."
        />

        <flux:select wire:model.live="category">

            <option value="">
                All Categories
            </option>

            <option value="makeup">
                Makeup
            </option>

            <option value="skincare">
                Skincare
            </option>

            <option value="haircare">
                Haircare
            </option>

        </flux:select>

    </div>

    <!-- Table -->
    <div class="overflow-x-auto">

        <flux:table>

            <flux:table.columns>

                <flux:table.column>
                    No
                </flux:table.column>

                <flux:table.column>
                    Product Code
                </flux:table.column>

                <flux:table.column>
                    Category Id
                </flux:table.column>

                <flux:table.column>
                    Name
                </flux:table.column>

                <flux:table.column>
                    Stock
                </flux:table.column>

                <flux:table.column>
                    Price
                </flux:table.column>

                <flux:table.column>
                    Expired Date
                </flux:table.column>


            </flux:table.columns>

            <flux:table.rows>

                @foreach ($this->products as $index => $product)

                    <flux:table.row :key="$product->id">

                        <flux:table.cell>
                            {{ $index + 1 }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->product_code }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->category_id }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->stock }}
                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->expired_date }}
                        </flux:table.cell>

                        <flux:table.cell>


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
                                        wire:click="edit({{ $product->id }})"
                                    >
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item
                                        variant="danger"
                                        icon="trash"
                                        wire:click="delete({{ $product->id }})"
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