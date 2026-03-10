<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use Filterable;

    protected $fillable = ['name', 'description', 'price', 'sku', 'image'];

    public function itemInventories()
    {
        return $this->hasMany(ItemInventory::class);
    }

    public function stocks()
    {
        return $this->hasManyThrough(Stock::class, ItemInventory::class);
    }

    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }
}
