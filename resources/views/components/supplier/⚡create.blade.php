<?php

use App\Livewire\Forms\SupplierForm;
use Livewire\Component;
use Flux\Flux;

new class extends Component
{
    public SupplierForm $form;

    public function save()
    {
        $this->form->store();

        Flux::modal('create-supplier')->close();

        session()->flash(
            'success',
            'Supplier created successfully'
        );

        $this->redirectRoute(
            'suppliers.index',
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
        name="create-supplier"
        class="md:w-150"
        x-on:close="$wire.resetForm()"
    >
        <form
            class="space-y-6"
            wire:submit.prevent="save"
        >
            <div>
                <flux:heading size="lg">
                    Create Supplier
                </flux:heading>

```
            <flux:text>
                Add new supplier
            </flux:text>
        </div>

        <flux:input
            label="Supplier Name"
            placeholder="Enter supplier name"
            wire:model="form.name"
        />

        <flux:input
            type="email"
            label="Email"
            placeholder="supplier@email.com"
            wire:model="form.email"
        />

        <flux:input
            label="Phone"
            placeholder="08123456789"
            wire:model="form.phone"
        />

        <flux:textarea
            label="Address"
            placeholder="Enter supplier address"
            wire:model="form.address"
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
                Create
            </flux:button>
        </div>
    </form>
</flux:modal>
```

</div>
