<?php

namespace App\Livewire\Forms;

use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Livewire\Form;

class SupplierForm extends Form
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public ?Supplier $supplier = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'nullable', 
                'email', 
                Rule::unique('suppliers', 'email')->ignore($this->supplier?->id)
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function setSupplier(Supplier $supplier): void
    {
        $this->supplier = $supplier;
        $this->name = $supplier->name;
        $this->email = $supplier->email ?? '';
        $this->phone = $supplier->phone ?? '';
        $this->address = $supplier->address ?? '';
    }

    public function store()
    {
        $this->validate();
        Supplier::create($this->only(['name', 'email', 'phone', 'address']));
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->supplier->update($this->only(['name', 'email', 'phone', 'address']));
    }
}