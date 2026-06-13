<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Product;

new class extends Component
{
    #[Computed]
    public function products()
    {
        return Product::latest()->get();
    }

    public function edit($id)
    {
        $this->dispatch('edit-product', id: $id);
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

    <!-- Tombol Add Product -->
    <flux:modal.trigger name="create-product">
        <flux:button variant="primary" icon="plus">
            Add Product
        </flux:button>
    </flux:modal.trigger>

    <!-- Modal Create -->
    <livewire:product.create />

    <!-- Modal Edit -->
    <livewire:product.edit />

    <!-- Tabel Product -->
    <div class="overflow-x-auto">

        <flux:table>

            <flux:table.columns>

                <flux:table.column>
                    Product Name
                </flux:table.column>

                <flux:table.column>
                    Category
                </flux:table.column>

                <flux:table.column>
                    Brand
                </flux:table.column>

                <flux:table.column>
                    Stock
                </flux:table.column>

                <flux:table.column>
                    Price
                </flux:table.column>

                <flux:table.column>
                    Status
                </flux:table.column>

                <flux:table.column>
                    Action
                </flux:table.column>

            </flux:table.columns>

            <flux:table.rows>

                @foreach ($this->products as $product)

                    <flux:table.row :key="$product->id">

                        <flux:table.cell>
                            {{ $product->product_name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->category }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->brand }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->stock }}
                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($product->price) }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->status }}
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
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>