<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Warehouse;

class ItemInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::all();
        $warehouses = Warehouse::all();

        foreach ($items as $item) {
            foreach ($warehouses as $warehouse) {
                DB::table('item_inventories')->insert([
                    'item_id' => $item->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => rand(10, 100),
                    'alert_level' => 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
