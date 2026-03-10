<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockTransfer;
use App\Models\Item;
use App\Models\Warehouse;

class StockTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::all();
        $warehouses = Warehouse::all();

        if ($warehouses->count() >= 2) {
            $from = $warehouses->first();
            $to = $warehouses->skip(1)->first();

            foreach ($items as $item) {
                StockTransfer::create([
                    'item_id' => $item->id,
                    'from_warehouse_id' => $from->id,
                    'to_warehouse_id' => $to->id,
                    'quantity' => rand(1, 10),
                    'type' => 1,
                ]);
            }
        }
    }
}
