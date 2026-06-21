<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CategoryForm extends Form
{
    public string $name = '';
    public string $category_code = '';
    public string $description = '';
    public ?Category $category = null;

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('categories', 'name')->ignore($this->category?->id),
            ],
            'category_code' => [
                'required', 'string', 'min:2', 'max:10',
                Rule::unique('categories', 'category_code')->ignore($this->category?->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->category_code = $category->category_code;
        $this->description = $category->description ?? '';
    }

    public function store()
    {
        $this->validate();
        Category::create($this->only(['name', 'category_code', 'description']));
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->category->update($this->only(['name', 'category_code', 'description']));
    }
}