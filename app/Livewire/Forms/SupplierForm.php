<?php

namespace App\Livewire\Forms;

use App\Models\Supplier;
use Livewire\Form;

class SupplierForm extends Form
{
    public string $name = '';
    public string $phone = '';
    public string $address = '';

    public ?Supplier $supplier = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'phone' => 'required',
            'address' => 'nullable',
        ];
    }

    public function store()
    {
        $this->validate();

        Supplier::create(
            $this->only([
                'name',
                'phone',
                'address'
            ])
        );

        $this->reset();
    }

    public function setSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;

        $this->name = $supplier->name;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
    }

    public function update()
    {
        $this->validate();

        $this->supplier->update(
            $this->only([
                'name',
                'phone',
                'address'
            ])
        );
    }

    public function delete()
    {
        $this->supplier?->delete();
    }
}