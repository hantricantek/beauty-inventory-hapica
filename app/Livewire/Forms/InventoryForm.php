<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class InventoryForm extends Form
{
    public string $product_name = '';

    public int $stock = 0;

    public string $unit = '';

    public ?Inventory $inventory = null;

    public function rules()
    {
        return [
            'product_name' => 'required',
            'stock' => 'required|integer|min:0',
            'unit' => 'nullable'
        ];
    }

    public function store()
    {
        $this->validate();

        Inventory::create(
            $this->only([
                'product_name',
                'stock',
                'unit'
            ])
        );

        $this->reset();
    }

}
