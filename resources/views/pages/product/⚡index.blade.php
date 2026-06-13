<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div >
    <flux:modal.trigger name="create-product">
    <flux:button
        variant="primary"
        icon="plus"
    >
        Add Product

    </flux:button>
    </flux:modal.trigger>
    <livewire:product.create />
</div>