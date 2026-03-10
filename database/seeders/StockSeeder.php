<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventories = DB::table('item_inventories')->get();

        foreach ($inventories as $inv) {
            Stock::create([
                'item_inventory_id' => $inv->id,
                'current_quantity' => $inv->quantity,
            ]);
        }
    }
}
