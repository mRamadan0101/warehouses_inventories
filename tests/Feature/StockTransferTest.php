<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\ItemInventory;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_stock_transfer_updates_quantities()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = Item::factory()->create();

        $fromInventory = ItemInventory::factory()->create([
            'warehouse_id' => $fromWarehouse->id,
            'item_id' => $item->id,
            'quantity' => 10,
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/stock-transfers', [
                'from_warehouse_id' => $fromWarehouse->id,
                'to_warehouse_id' => $toWarehouse->id,
                'item_id' => $item->id,
                'quantity' => 4,
            ])
            ->assertStatus(201);

        $fromInventory->refresh();
        $this->assertEquals(6, $fromInventory->quantity);

        $this->assertDatabaseHas('item_inventories', [
            'warehouse_id' => $toWarehouse->id,
            'item_id' => $item->id,
            'quantity' => 4,
        ]);
    }
}

