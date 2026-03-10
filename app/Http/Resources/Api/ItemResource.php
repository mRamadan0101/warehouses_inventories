<?php

namespace App\Http\Resources\Api;

use App\Models\ItemInventory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    protected $quantity = null;

    public function setQunitity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $item = $this;

        if ($this->resource instanceof ItemInventory) {
            $item = $this->item;
        }

        return [
            'id' => (int) $item->id,
            'name' => (string) $item->name,
            'description' => (string) $item->description,
            'price' => floatval($item->price),
            'sku' => (string) $item->sku,
            'quantity' => floatval($this->quantity),
            'inventory_details' => new InventoryResourceCollection($item->itemInventories->where('warehouse_id', $this->resource->warehouse_id)),
        ];
    }
}
