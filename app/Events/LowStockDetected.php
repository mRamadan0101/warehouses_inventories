<?php

namespace App\Events;

use App\Models\ItemInventory;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockDetected
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public ItemInventory $itemInventory;

    /**
     * Create a new event instance.
     */
    public function __construct(ItemInventory $itemInventory)
    {
        $this->itemInventory = $itemInventory;
    }
}

