<?php

namespace Tests\Feature;

use App\Events\LowStockDetected;
use App\Models\Item;
use App\Models\ItemInventory;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LowStockEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_stock_event_is_dispatched_when_quantity_below_alert_level()
    {
        Event::fake([LowStockDetected::class]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = Item::factory()->create();

        $inventory = ItemInventory::factory()->create([
            'warehouse_id' => $fromWarehouse->id,
            'item_id' => $item->id,
            'quantity' => 5,
            'alert_level' => 4,
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/stock-transfers', [
                'from_warehouse_id' => $fromWarehouse->id,
                'to_warehouse_id' => $toWarehouse->id,
                'item_id' => $item->id,
                'quantity' => 2,
            ])
            ->assertStatus(201);

        Event::assertDispatched(LowStockDetected::class, function (LowStockDetected $event) use ($inventory) {
            return $event->itemInventory->id === $inventory->id;
        });
    }
}

