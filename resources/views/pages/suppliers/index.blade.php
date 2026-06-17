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

    <div class="overflow-x-auto">

        <flux:table>

            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Address</flux:table.column>
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

                    </flux:table.row>

                @endforeach

            </flux:table.rows>

        </flux:table>

    </div>

</div>