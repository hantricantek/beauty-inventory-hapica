<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\ProductForm;
use App\Models\Product;

new class extends Component
{
    public ProductForm $form;

    #[On('edit-product')]
    public function editProduct($id)
    {
        $product = Product::find($id);

        $this->form->setProduct($product);

        Flux::modal('edit-product')->show();
    }

    public function updateProduct()
    {
        $this->form->update();

        Flux::modal('edit-product')->close();

        session()->flash(
            'success',
            'Product updated successfully'
        );

        $this->redirectRoute(
            'product.index',
            navigate: true
        );
    }

    #[On('confirm-delete')]
    public function confirmDelete($id)
    {
        $product = Product::find($id);

        $this->form->setProduct($product);

        Flux::modal('delete-product')->show();
    }

    public function deleteProduct()
    {
        $this->form->delete();

        Flux::modal('delete-product')->close();

        session()->flash(
            'success',
            'Product deleted successfully'
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

    <!-- Modal Edit -->
    <flux:modal
        name="edit-product"
        class="md:w-150"
        x-on:close="$wire.resetForm()"
    >

        <form
            class="space-y-6"
            wire:submit.prevent="updateProduct"
        >

            <div>
                <flux:heading size="lg">
                    Edit Product
                </flux:heading>

                <flux:text>
                    Update beauty product information
                </flux:text>
            </div>

            <flux:input
                label="Product Name"
                wire:model="form.product_name"
            />

            <flux:input
                label="Category"
                wire:model="form.category"
            />

            <flux:input
                label="Brand"
                wire:model="form.brand"
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
                    <flux:button variant="outline">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Update
                </flux:button>

            </div>

        </form>

    </flux:modal>


    <!-- Modal Delete -->
    <flux:modal
        name="delete-product"
        class="md:w-100"
        x-on:close="$wire.resetForm()"
    >

        <form wire:submit.prevent="deleteProduct">

            <div class="space-y-4">

                <flux:heading size="lg">
                    Delete Product
                </flux:heading>

                <flux:text>
                    Are you sure you want to delete this product?
                </flux:text>

                <div class="flex justify-end gap-3">

                    <flux:modal.close>

                        <flux:button variant="outline">
                            Cancel
                        </flux:button>

                    </flux:modal.close>

                    <flux:button
                        type="submit"
                        variant="danger"
                    >
                        Delete
                    </flux:button>

                </div>

            </div>

        </form>

    </flux:modal>

</div>