<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\ItemInventory;
use App\Models\StockTransfer;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_transfer_more_than_available_stock()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = Item::factory()->create();

        ItemInventory::factory()->create([
            'warehouse_id' => $fromWarehouse->id,
            'item_id' => $item->id,
            'quantity' => 5,
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/stock-transfers', [
            'from_warehouse_id' => $fromWarehouse->id,
            'to_warehouse_id' => $toWarehouse->id,
            'item_id' => $item->id,
            'quantity' => 10,
        ]);

        $response->assertStatus(400);
        $this->assertEquals(0, StockTransfer::count());
    }
}

