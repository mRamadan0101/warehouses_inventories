<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'name' => 'Laptop',
            'description' => 'High-performance laptop',
            'price' => 1200.00,
            'sku' => 'LAP001',
        ]);

        Item::create([
            'name' => 'Mouse',
            'description' => 'Wireless mouse',
            'price' => 25.00,
            'sku' => 'MOU001',
        ]);

        Item::create([
            'name' => 'Keyboard',
            'description' => 'Mechanical keyboard',
            'price' => 80.00,
            'sku' => 'KEY001',
        ]);

        Item::create([
            'name' => 'Monitor',
            'description' => '27-inch monitor',
            'price' => 300.00,
            'sku' => 'MON001',
        ]);
    }
}
