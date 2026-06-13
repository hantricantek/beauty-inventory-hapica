<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Form;

class ProductForm extends Form
{
    public string $product_name = '';
    public string $category = '';
    public string $brand = '';
    public int $stock = 0;
    public string $price = '';
    public string $status = '';
    public ?Product $product = null;
    public function rules(): array
    {
        return [
            'product_name' => 'required|min:3|max:255',
            'category' => 'required',
            'brand' => 'required',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required',
        ];
    }

    public function store()
    {
        $this->validate();

        Product::create(
            $this->only([
                'product_name',
                'category',
                'brand',
                'stock',
                'price',
                'status',
            ])
        );

    }
    public function delete()
    {
        $this->product->delete();

        $this->reset();
    }


    public function setProduct(Product $product): void
    {
        $this->product = $product;
        $this->product_name = $product->product_name;
        $this->category = $product->category;
        $this->brand = $product->brand;
        $this->stock = $product->stock;
        $this->price = $product->price;
        $this->status = $product->status;
    }

    public function update()
    {
        $this->validate();

        $this->product->update(
            $this->only([
                'product_name',
                'category',
                'brand',
                'stock',
                'price',
                'status',
            ])
        );
    }
}