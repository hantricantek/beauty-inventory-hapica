<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use App\Models\Supplier;

class CreateSupplier extends Component
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

        return $this->redirectRoute('supplier.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.suppliers.create-supplier');
    }
}