<?php

use App\Livewire\Forms\ProductForm;
use Livewire\Component;

new class extends Component
{
    public ProductForm $form;
    public function save()
    {
        $this->form->store();

        Flux::modal('create-product')->close();

        session()->flash(
            'success',
            'Product created successfully'
        );

        $this->redirectRoute(
            'product.index',
            navigate: true
        );
    }

    public function resetForm()
    {
        $this->resetValidation();

        $this->form->reset();
    }
};

?>

<div>

    <flux:modal
        name="create-product"
        class="md:w-150"
        x-on:close="$wire.resetForm()"
    >

        <form
            class="space-y-6"
            wire:submit.prevent="save"
        >

            <div>
                <flux:heading size="lg">
                    Create Product
                </flux:heading>

                <flux:text>
                    Add new beauty product
                </flux:text>
            </div>

            <flux:input
                label="Product Code"
                placeholder="Enter product code"
                wire:model="form.product_code"
            />

            <flux:input
                label="Category ID"
                placeholder="Skincare, Makeup..."
                wire:model="form.category_id"
            />

            <flux:input
                label="Name"
                placeholder="Wardah, Emina..."
                wire:model="form.name"
            />

            <flux:input
                type="number"
                label="Stock"
                wire:model="form.stock"
            />

            <flux:input
                type="number"
                label="Price"
                wire:model="form.price"
            />

            <flux:select
                label="Status"
                wire:model="form.status"
            >

                <option value="">
                    Select Status
                </option>

                <option value="Available">
                    Available
                </option>

                <option value="Low Stock">
                    Low Stock
                </option>

                <option value="Out of Stock">
                    Out of Stock
                </option>

            </flux:select>

            <div class="flex justify-end gap-3">

                <flux:modal.close>

                    <flux:button
                        variant="outline"
                    >
                        Cancel
                    </flux:button>

                </flux:modal.close>

                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Create
                </flux:button>

            </div>

        </form>

    </flux:modal>

</div>