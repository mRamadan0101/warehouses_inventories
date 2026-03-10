<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\ItemInventory;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_returns_paginated_warehouses_with_inventories()
    {
        // Create test data
        $warehouse = Warehouse::factory()->create();
        $item = Item::factory()->create();
        ItemInventory::factory()->create([
            'warehouse_id' => $warehouse->id,
            'item_id' => $item->id,
            'quantity' => 10,
        ]);

        // Act
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/inventory');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'item_inventories' => [
                                '*' => [
                                    'id',
                                    'quantity',
                                    'item' => [
                                        'id',
                                        'name',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'current_page',
                    'per_page',
                ],
            ]);
    }

    public function test_show_returns_specific_warehouse_inventory()
    {
        // Create test data
        $warehouse = Warehouse::factory()->create();
        $item = Item::factory()->create();
        ItemInventory::factory()->create([
            'warehouse_id' => $warehouse->id,
            'item_id' => $item->id,
            'quantity' => 5,
        ]);

        // Act
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/warehouses/{$warehouse->id}/inventory");

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'name',
                    'item_inventories' => [
                        '*' => [
                            'id',
                            'quantity',
                            'item' => [
                                'id',
                                'name',
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function test_show_returns_404_for_nonexistent_warehouse()
    {
        // Act
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/warehouses/999/inventory');

        // Assert
        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_access_inventory()
    {
        // Act
        $response = $this->getJson('/api/inventory');

        // Assert
        $response->assertStatus(401);
    }
}