<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
     protected $fillable = [
        'inventory_id',
        'qty',
        'date',
        'notes'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}