<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StocktransferRequest;
use App\Models\ItemInventory;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;
use App\Events\LowStockDetected;

class StockTransferController extends Controller
{
    public function store(StocktransferRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated) {
            // Check if from warehouse has enough stock
            $fromInventory = ItemInventory::where('warehouse_id', $validated['from_warehouse_id'])
                ->where('item_id', $validated['item_id'])
                ->lockForUpdate()
                ->first();

            if (!$fromInventory || $fromInventory->quantity < $validated['quantity']) {
                return $this->setCode(400)
                    ->setError(__('messages.insufficient_stock'))
                    ->send();
            }

            // Create stock transfer
            $transfer = StockTransfer::create([
                'from_warehouse_id' => $validated['from_warehouse_id'],
                'to_warehouse_id' => $validated['to_warehouse_id'],
                'item_id' => $validated['item_id'],
                'quantity' => $validated['quantity'],
                'user_id' => auth()->id(),
                'type' => StockTransfer::TRANSFER,
            ]);

            // Update inventories
            $fromInventory->decrement('quantity', $validated['quantity']);

            $toInventory = ItemInventory::firstOrCreate(
                ['warehouse_id' => $validated['to_warehouse_id'], 'item_id' => $validated['item_id']],
                ['quantity' => 0]
            );
            $toInventory->increment('quantity', $validated['quantity']);

            if ($fromInventory->quantity <= $fromInventory->alert_level) {
                LowStockDetected::dispatch($fromInventory);
            }

            return $this->setCode(201)
                ->setMessage(__('messages.transfer_success'))
                ->setData($transfer)
                ->send();
        });
    }
}
