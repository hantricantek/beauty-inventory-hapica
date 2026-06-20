<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'category_id',
        'name',
        'stock',
        'price',
        'expired_date',
    ];

    // Relasi ke tabel stock_ins
    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    // Relasi ke tabel stock_outs
    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }
}