<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['item_inventory_id', 'current_quantity'];

    public function itemInventory()
    {
        return $this->belongsTo(ItemInventory::class);
    }

    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }
}
