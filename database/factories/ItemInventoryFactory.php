<?php

namespace Database\Factories;

use App\Models\ItemInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemInventoryFactory extends Factory
{
    protected $model = ItemInventory::class;

    public function definition(): array
    {
        return [
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'item_id' => \App\Models\Item::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}