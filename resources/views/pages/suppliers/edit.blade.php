<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Supplier;

new class extends Component
{
    public ?Supplier $supplier = null;

    public string $name = '';
    public string $phone = '';
    public string $address = '';

    #[On('edit-supplier')]
    public function editSupplier($id)
    {
        $this->supplier = Supplier::find($id);

        $this->name = $this->supplier->name;
        $this->phone = $this->supplier->phone;
        $this->address = $this->supplier->address;

        Flux::modal('edit-supplier')->show();
    }

    public function updateSupplier()
    {
        $this->validate([
            'name' => 'required|min:3',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $this->supplier->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        Flux::modal('edit-supplier')->close();

        session()->flash(
            'success',
            'Supplier updated successfully'
        );

        $this->redirectRoute(
            'supplier.index',
            navigate: true
        );
    }
};

?>

<div>

    <flux:modal
        name="edit-supplier"
        class="md:w-150"
    >

        <form
            class="space-y-6"
            wire:submit.prevent="updateSupplier"
        >

            <div>

                <flux:heading size="lg">
                    Edit Supplier
                </flux:heading>

                <flux:text>
                    Update supplier information
                </flux:text>

            </div>

            <flux:input
                label="Supplier Name"
                wire:model="name"
            />

            <flux:input
                label="Phone"
                wire:model="phone"
            />

            <flux:textarea
                label="Address"
                wire:model="address"
            />

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

</div>