<?php

use Livewire\Component;
use App\Models\Supplier;

new class extends Component
{
    public string $name = '';
    public string $phone = '';
    public string $address = '';

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'phone' => 'required',
            'address' => 'required',
        ]);

        Supplier::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        $this->reset();

        Flux::modal('create-supplier')->close();

        session()->flash(
            'success',
            'Supplier created successfully'
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
        name="create-supplier"
        class="md:w-150"
    >

        <form
            class="space-y-6"
            wire:submit.prevent="save"
        >

            <div>

                <flux:heading size="lg">
                    Create Supplier
                </flux:heading>

                <flux:text>
                    Add new supplier
                </flux:text>

            </div>

            <flux:input
                label="Supplier Name"
                placeholder="Enter supplier name"
                wire:model="name"
            />

            <flux:input
                label="Phone"
                placeholder="08xxxxxxxxxx"
                wire:model="phone"
            />

            <flux:textarea
                label="Address"
                wire:model="address"
            />

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