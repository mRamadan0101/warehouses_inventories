<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = $this->itemInventories->groupBy('item_id')->map(function ($itemInventories) {
            return (new ItemResource($itemInventories->first()))->setQunitity($itemInventories->sum('quantity'));
        });

        return [
            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'items' => $items->values(),
            ];
    }
}
