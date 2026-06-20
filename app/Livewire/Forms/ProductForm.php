<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Form;

class ProductForm extends Form
{
    public string $product_code = '';
    public string $name = '';
    public string $category_id = '';
    public int $stock = 0;
    public string $price = '';
    public string $expired_date = '';

    public ?Product $product = null;

    public function rules(): array
    {
        return [
            'product_code' => 'required|max:255',
            'name' => 'required|min:3|max:255',
            'category_id' => 'required',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expired_date' => 'required|date',
        ];
    }

    public function store()
    {
        $this->validate();

        Product::create([
            'product_code' => $this->product_code,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'stock' => $this->stock,
            'price' => $this->price,
            'expired_date' => $this->expired_date,
        ]);

        $this->reset();
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;

        $this->product_code = $product->product_code;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->stock = $product->stock;
        $this->price = $product->price;
        $this->expired_date = $product->expired_date;
    }

    public function update()
    {
        $this->validate();

        $this->product->update([
            'product_code' => $this->product_code,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'stock' => $this->stock,
            'price' => $this->price,
            'expired_date' => $this->expired_date,
        ]);
    }

    public function delete()
    {
        $this->product->delete();

        $this->reset();
    }
}