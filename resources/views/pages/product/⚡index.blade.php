<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <flux:modal.trigger name="create-product">
        <flux:button variant="primary" icon="plus">
            Add Product

        </flux:button>
    </flux:modal.trigger>
    <livewire:product.create />

    <h1>Hello balinux was here</h1>
</div>